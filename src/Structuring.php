<?php

namespace SaatyMethod;

use Exception;

/**
 * Class contains methods for structuring the selection problem 
 * as a hierarchy or network.
 *
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
class Structuring
{
    private $purpose;
    
    private $criterion = [];
    
    private $alternative = [];

    private $initial_data = [];
    
    public function setPurpose(string $purpose) : void
    {
            $this->purpose = $purpose;
    }
    
    public function setCriterion($criterion) : void
    {
        if($this->isEmptyStringCriterion($criterion)) {
            $this->criterion[] = $criterion;
        } elseif ($this->isEmptyArrayCriterion($criterion)) {
            $this->criterion = $criterion;
        } else {
            throw new Exception('Invalid data format. You must set an array or strings'); 
        }
    }
    
    public function setAlternative($alternative) : void
    {
        if($this->isEmptyStringAlternative($alternative)) {
            $this->alternative[] = $alternative;
        } elseif ($this->isEmptyArrayAlternative($alternative)) {
            $this->alternative = $alternative;
        } else {
            throw new Exception('Invalid data format. You must set an array or strings'); 
        }
    }
    
    public function setInitialData(string $criterion, array $alternative) : void
    {
        $this->initial_data[$criterion][$alternative['0']] = $alternative['1'];
    }
    
    public function getPurpose() : string
    {
        return $this->purpose;
    }
    
    public function getCriterion() : array
    {
        return $this->criterion;
    }
    
    public function getAlternative() : array
    {
        return $this->alternative;
    }
    
    public function getInitialData() : array
    {
        return $this->initial_data;
    }

    public function isEmptyStringAlternative($alternative) : bool
    {
        return is_string($alternative) ? true : false;
    }
    
    public function isEmptyArrayAlternative($alternative) : bool
    {
        return is_array($alternative) && empty($this->alternative) ? true : false;
    }
    
    public function isEmptyStringCriterion($criterion) : bool
    {
        return is_string($criterion) ? true : false;
    }
    
    public function isEmptyArrayCriterion($criterion) : bool
    {
        return is_array($criterion) && empty($this->criterion) ? true : false;
    }
}
