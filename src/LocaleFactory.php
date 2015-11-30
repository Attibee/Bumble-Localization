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

namespace Bumble\Locale;

/**
 * Loads and creates a new Locale given the Locale identifier.
 */
class LocaleFactory {
    /**
     * Gets the Locale from the identifier.
     * 
     * @param string $identifier The identifier of the Locale, such as en_US.
     * 
     * @return \Bumble\Locale\Locale The Locale object, or null if not found.
     */
    static public function getLocale( $identifier ) {
        $resourceDir = __DIR__ . '/../resources/';
    
        //let's support both formats
        $identifier = str_replace( '-', '_', $identifier );
        $file = $resourceDir . $identifier . '.xml';

        //file does not exist, return null
        if( !file_exists( $file ) ) {
            return null;
        }
    
        $locale = new Locale( $identifier );

        //create reader
        $dom = new \DOMDocument();
        $dom->load( $file );

        //add reader to locale resources
        $locale->setDom( $dom );

        //add parents by successively removing "_suffix"
        //these are added recursively
        $pos = strrpos( $identifier, '_' );
    
        if( $pos !== false ) {
            $identifier = substr( $identifier, 0, $pos );
            $locale->setParentLocale( LocaleFactory::getLocale( $identifier ) );
        }
    
        return $locale;
    }
}
?>