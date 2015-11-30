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

class NumberGetter extends Getter {
    protected $name;
    protected $options = array(
        'lang' => null,
        'length' => null,
        'type' => null
    );
    
    public function get() {
        $name = $this->name;
        $lang = $this->getOption('lang');
        $length = $this->getOption('length');
        $type = $this->getOption('type');
        
        //get default lang?
        if( !$lang )
            $lang = $this->locale->defaultNumberingSystem();
        
        //process length
        if( !$length )
            $length = 'not(@type)';
        else
            $length = "@type='$length'";
        
        //process type
        if( !$type )
            $type = "@type='standard' or not(@type)";
        else
            $type = "@type='$type'";
        
        $nodes = $this->locale->queryCollection("
            /ldml
            /numbers
            /{$name}Formats[@numberSystem='$lang']
            /{$name}FormatLength[$length]
            /{$name}Format[$type]
            /pattern
        ");

        if( $nodes ) {
            $patterns = new \Bumble\Locale\Pattern\PatternCollection;
            
            foreach( $nodes as $pattern ) {
                $type = $pattern->getAttribute('type') ? $pattern->getAttribute('type') : null;
                $count = $pattern->getAttribute('count') ? $pattern->getAttribute('count') : null;
                $value = $pattern->textContent;
                
                $pattern = new \Bumble\Locale\Pattern\Pattern();
                $pattern->type = $type;
                $pattern->count = $count;
                $pattern->value= $value;
                
                $patterns->addPattern( $pattern );
            }
            
            $this->resetOptions();
            
            return $patterns;
        }
    }
}