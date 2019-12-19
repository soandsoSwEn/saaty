<?php

namespace SaatyMethod;

/**
 * StructuringInterface is an interface that defines the first step in applying
 * the hierarchy analysis method - structuring the selection problem in 
 * the form of a hierarchy or network. In the most elementary form, 
 * the hierarchy is built from the top (goal), through intermediate 
 * levels - criteria to the lowest level, which in the general case
 * is a set of alternatives.
 * 
 * @author Dmytriyenko Vyacheslav <dmytriyenko.vyacheslav@gmail.com>
 */
interface StructuringInterface
{
    public function setCriterion($criterion) : void;
    
    public function setAlternative($alternative) : void;
    
    public function setInitialData(string $criterion, array $alternative) : void;
    
    public function getCriterion() : array;
    
    public function getAlternative() : array;
    
    public function getInitialData() : array;
}
