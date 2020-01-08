<?php

namespace SaatyMethod;

/**
 * HierarchyInterface is an interface that defines methods for evaluating
 * each alternative by criteria and obtaining a final vector of priorities,
 * based on which the best of alternatives is determined
 * 
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
interface HierarchyInterface
{
    public function setStructureData(Structuring $data) : void;
    
    public function getStructureData() : object;
    
    public function setSummaryData(array $pairwise_comparisons) : void;
    
    public function getSummaryData() : array;
    
    public function getBestAlternative(array $data) : string;
}
