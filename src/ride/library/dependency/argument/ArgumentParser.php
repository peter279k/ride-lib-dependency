<?php

namespace ride\library\dependency\argument;

use ride\library\dependency\DependencyCallArgument;

/**
 * Parser for the dependency call arguments
 */
interface ArgumentParser {

    /**
     * Gets the actual value of the argument
     * @param \ride\library\dependency\DependencyCallArgument $argument Argument
     * definition
     * @return mixed The value
     */
    public function getValue(DependencyCallArgument $argument);

    /**
     * Checks if this argument is scalar or needs intelligence
     * @return boolean True if this argument needs intelligence, false otherwise
     */
    public function needsIntelligence();

    /**
     * Gets the intelligence of this argument
     * @param \ride\library\dependency\DependencyCallArgument $argument Argument
     * definition
     * @return \ride\library\dependency\DependencyCallArgument
     */
    public function getIntelligence(DependencyCallArgument $argument);

}
