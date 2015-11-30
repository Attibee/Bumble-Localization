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

/**
 * The SymbolGetter gets the number symbols of a specific language.
 *
 * $locale->symbol->lang('latn')->symbol('plusSign')->get()
 * $locale->symbol(array('lang' => 'latn', 'symbol' => 'exponential'))
 */
class SymbolGetter extends Getter  {
    protected $options = array(
        'lang' => null,
        'symbol' => null
    );
    
    public function get() {
        $lang = $this->getOption('lang');
        $symbol = $this->getOption('symbol');
        
        if( !$lang ) {
            $lang = $this->locale->defaultNumberingSystem();
        }
        
        //no symbol, return null
        if( !$symbol ) {
            return null;
        }
        
        $nodes = $this->locale->queryCollection("/ldml/numbers/symbols[@numberSystem='$lang']/$symbol");
        
        if( !$nodes )
            return null;
    
        //always reset after we get
        $this->resetOptions();
        
        return $nodes->item(0)->textContent;
    }
}