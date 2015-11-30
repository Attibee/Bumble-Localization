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

class DefaultNumberingSystemGetter extends Getter {
    private $numberingSystem = false;
    
    public function get() {
        //not yet set, let's set the system
        if( $this->numberingSystem === false ) {
            $nodes = $this->locale->queryCollection('/ldml/numbers/defaultNumberingSystem/text()');
            
            //no default, let's infer
            if( $nodes ) {
                $this->numberingSystem = $nodes->item(0)->wholeText;
            } else {
                //let's infer it from the number system attribute
                $nodes = $this->locale->queryCollection('/ldml/numbers/*/@numberSystem[1]');
                    
                if( $nodes ) {
                    $this->numberingSystem = $nodes->item(0)->value;
                } else {
                    $this->numberingSystem = null;
                }
            }
        }
        
        //return it
        return $this->numberingSystem;
    }
}