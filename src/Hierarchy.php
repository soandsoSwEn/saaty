<?php

namespace SaatyMethod;

use Exception;

/**
 * Description of Hierarchy
 *
 * @author Vyacheslav
 */
class Hierarchy
{
    private $structure;
    
    private $judgment_matrix = [];
    
    private $pairwise_comparisons = [];
    
    private $eigenvector = 0;
    
    private $L = 0;
    
    private $n = 0;

    private $consistency_index = 0;
    
    private $random_consistency_matrix = [
        1  => 0,
        2  => 0,
        3  => 0.58,
        4  => 0.9,
        5  => 1.12,
        6  => 1.24,
        7  => 1.32,
        8  => 1.41,
        9  => 1.45,
        10 => 1.49,
    ];
    
    private $random_consistency = 0;

    private $relative_consistency = 0;
    
    /**
     * Limit value of relative consistency
     */
    const MAX_REL_CONS = 10;
    
    private $prioritet;
    

    public function __construct(Structuring $data)
    {
        $this->setStructureData($data);
    }
    
    public function setStructureData($data) : void
    {
        $this->structure = $data;
    }
    
    public function getStructureData() : object
    {
        return $this->structure;
    }
    
    public function setPriorityFactor(string $criterion1, string $criterion2, float $coefficient) : void
    {
        if($this->isCriterion($criterion1, $criterion2)) {
            $this->judgment_matrix[$criterion1][$criterion2] = $coefficient;
        } else {
            throw new Exception('Incorrectly specified evaluation criteria. They do not match the source data.');
        }
    }
    
    public function getJudgment_matrix() : array
    {
        return $this->judgment_matrix;
    }
    
    public function isCriterion(string $criterion1, string $criterion2) : bool
    {
        $structuring = $this->getStructureData();
        $data = $structuring->getInitialData();
        $criterion_candidate1 = false;
        $criterion_candidate2 = false;
        foreach ($data as $criterion_data => $lternative_data) {
            if(strcasecmp($criterion_data, $criterion1) == 0 ) {
                $criterion_candidate1 = true;
                break;
            }
        }
        
        foreach ($data as $criterion_data => $lternative_data) {
            if(strcasecmp($criterion_data, $criterion2) == 0 ) {
                $criterion_candidate2 = true;
                break;
            }
        }
        
        if($criterion_candidate1 && $criterion_candidate2) {
            return true;
        } else {
            return false;
        }
    }
    
    public function setComponentEigenvector(array $judgment_matrix) : void
    {
        foreach ($judgment_matrix as $criterion => $value) {
            $this->judgment_matrix[$criterion]['eigenvector'] = $this->calCompEigenvector($value);
        }
    }
    
    public function calCompEigenvector(array $data) : float
    {
        (int)$i = 0;
        $comp = 1;
        foreach ($data as $value) {
            $comp = $comp * $value;
            $i++;
        }
        return pow($comp, 1/$i);
    }
    
    public function setComponentPriorityvector(array $judgment_matrix) : void
    {
        foreach ($judgment_matrix as $criterion => $value) {
            $this->judgment_matrix[$criterion]['priority_vector'] = $this->calComppriorityVector($value);
        }
    }
    
    public function setEigenvector(array $data) : void
    {
        $this->eigenvector = 0;
        foreach ($data as $key => $value) {
            $this->eigenvector = $this->eigenvector + $value['eigenvector'];
        }
    }

    public function calComppriorityVector(array $data) : float
    {
        return $data['eigenvector']/$this->eigenvector;
    }
    
    public function calcL(array $data) : void
    {
        $this->L = 0;
        foreach ($data as $criteria => $comp_criteria) {
            foreach ($comp_criteria as $key => $value) {
                if(!strcasecmp($key, 'eigenvector') == 0 && !strcasecmp($key, 'priority_vector') == 0) {
                    $this->L = $this->L + $value * $comp_criteria['priority_vector'];
                }
            }
        }
    }
    
    public function calcConsistencyIndex(array $data) : void
    {
        $this->n = 0;
        foreach ($data as $criteria => $comp_value) {
            $this->n = count($comp_value) - 2; break;
        }
        $this->consistency_index = ($this->L-$this->n)/($this->n-1);
    }
    
    
    public function calcRandomConsistency() : void
    {
        if(!$this->n) {
            throw new Exception();
        }
        foreach ($this->random_consistency_matrix as $key => $value) {
            if ($key == $this->n) {
                $this->random_consistency = $value;
            }
        }
    }
    
    public function calcRelativeConsistency() : void
    {
        if(!$this->consistency_index) {
            throw new Exception('Before calculating relative consistency, it is necessary to calculate the consistency index');
        }
        if(!$this->random_consistency) {
            throw new Exception();
        }
        $this->relative_consistency = $this->consistency_index / $this->random_consistency * 100;
    }
    
    public function isPermissible(float $data) : bool
    {
        return $data <= self::MAX_REL_CONS ? true : false;
    }
    
    public function setPairwiseComparisons() : void
    {
        $source = $this->getStructureData();
        $criterions = $source->getCriterion();
        $alternatives = $source->getAlternative();
        foreach ($criterions as $criterion) {
            foreach ($alternatives as $alternative) {
                foreach ($alternatives as $one_alternative) {
                    $this->pairwise_comparisons[$criterion][$alternative][$one_alternative] = 0;
                }
            }
        }
    }
    
    public function getPairwiseComparisons() : array
    {
        return $this->pairwise_comparisons;
    }

    public function setAlternativeAssessment(string $criterion, string $alternative1, string $alternative2, float $coefficient) : void
    {
        if(!isset($this->pairwise_comparisons[$criterion][$alternative1][$alternative2])) {
            throw new Exception('The parameters of the matrix are incorrect');
        }
        $this->pairwise_comparisons[$criterion][$alternative1][$alternative2] = $coefficient;
    }
    
    public function setCompEigenvectorComparisons(array $pairwise_comparisons)
    {
        foreach ($pairwise_comparisons as $criterion => $alternatives) {
            foreach ($alternatives as $key => $value) {
                $this->pairwise_comparisons[$criterion][$key]['eigenvector'] = $this->calCompEigenvectorComparison($value);
            }
        }
    }
    
    public function calCompEigenvectorComparison(array $data) : float
    {
        (int)$i = 0;
        $comp = 1;
        foreach ($data as $value) {
            $comp = $comp * $value;
            $i++;
        }
        return pow($comp, 1/$i);
    }
    
    public function setEigenvectorComparison(array $data)
    {
        $this->eigenvector = 0;
        foreach ($data as $criterion => $alternatives) {
            $this->eigenvector = $this->calcEigenvectorComparison($alternatives);
            $this->pairwise_comparisons[$criterion]['eigenvector'] = $this->eigenvector;
            $this->eigenvector = 0;
        }
    }
    
    public function calcEigenvectorComparison(array $data) : float
    {
        (float)$result = 0;
        foreach ($data as $value) {
            $result = $result + $value['eigenvector'];
        }
        return $result;
    }
    
    public function setCompPriorityvectorComparisons(array $pairwise_comparisons) : void
    {
        foreach ($pairwise_comparisons as $criterion => $alternatives) {
            foreach ($alternatives as $key => $value) {
                if(!is_array($value)) {
                    continue;
                }
                $this->pairwise_comparisons[$criterion][$key]['priority_vector'] = $this->calcCompPriorityvectorComparisons($value, $this->pairwise_comparisons[$criterion]['eigenvector']);
            }
            unset($this->pairwise_comparisons[$criterion]['eigenvector']);
        }
    }
    
    public function calcCompPriorityvectorComparisons(array $data, float $eigenvector) : ?float
    {
        return $eigenvector != 0 ? $data['eigenvector']/$eigenvector : null;
    }
    
    public function calcLC(string $criteria, array $pairwise_comparisons) : void
    {
        $this->L = 0;
        foreach ($pairwise_comparisons[$criteria] as $key => $alternatives) {
            foreach ($alternatives as $alternative => $value) {
                if (!strcasecmp($alternative, 'eigenvector') == 0 && !strcasecmp($alternative, 'priority_vector') == 0) {
                    $this->L = $this->L + $value * $this->pairwise_comparisons[$criteria][$alternative]['priority_vector'];
                }
            }
        }
    }
    
    public function calcConsIndexComparisons(string $criteria, array $pairwise_comparisons)
    {
        $this->n = 0;
        foreach ($pairwise_comparisons[$criteria] as $key => $alternatives) {
            $this->n = count($alternatives) - 2; break;
        }
        $this->consistency_index = ($this->L-$this->n)/($this->n-1);
    }
}
