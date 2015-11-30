<?php

/* Copyright 2015 Attibee (http://attibee.com)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Bumble\Locale\Getter;

abstract class Getter {
    protected $locale = null;
    protected $options = array();
    protected $abstractOptions = array();
    
    public function __construct( \Bumble\Locale\Locale $l ) {
        $this->locale = $l;
    }
    
    /**
     * The invoke option takes an array of options, and sets
     * and verifies the options and calls the get() method.
     */
    public function __invoke( $options ) {
        foreach( $options as $key=>$value ) {
            if( isset( $this->options[$key] ) )
                $this->abstractOptions[$key] = $value;
        }
        
        return $this->get();
    }
    
    /**
     * The call method provides a chaining method to setting
     * options as it's not always desirable to pass in large
     * arrays of options.
     *
     * @param string $name   the name of the option
     * @param mixed  $params an array of parameters
     *
     * @return $this to enable chaining of options
     */
    public function __call( $name, $params ) {
        if( array_key_exists( $name, $this->options ) )
            $this->abstractOptions[$name] = $params[0];
        
        return $this;
    }
    
    public function resetOptions() {
        $this->abstractOptions = array();
    }
    
    public function getOption( $key ) {
        if( !array_key_exists( $key, $this->options ) )
            throw new \Bumble\Locale\Exception\InvalidOptionException("$key is an invalid option.");
        
        if( array_key_exists( $key, $this->abstractOptions ) ) {
            return $this->abstractOptions[$key]; //give the set option, if it's set
        } else {
            return $this->options[$key]; //give the default value
        }
    }
    
    abstract public function get();
}