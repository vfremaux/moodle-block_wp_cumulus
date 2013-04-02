<?php
/*
    @using : 
	Plugin Name: WP-Cumulus
	Plugin URI: http://www.roytanck.com/2008/03/15/wp-cumulus-released
	Description: Flash based Tag Cloud for WordPress
	Version: 1.23
	Author: Roy Tanck
	Author URI: http://www.roytanck.com
	
	Copyright 2009, Roy Tanck

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class block_wp_cumulus extends block_base {

    function init() {
        $this->title = get_string('blockname', 'block_wp_cumulus');
        $this->version = 2010042400;
    }

    function applicable_formats() {
        return array('all' => true);
    }

    function specialization() {
        if (empty($this->config)){
            $this->config->width = '160';
	        $this->config->height = '160';
	        $this->config->tcolor = '000000';
	        $this->config->tcolor2 = '800000';
	        $this->config->hicolor = '808000';
	        $this->config->bgcolor = 'ffffff';
	        $this->config->speed = '100';
	        $this->config->tagstyle = '12';
	        $this->config->trans = 0;
	        $this->config->distr = 1;
            $this->config->args = '';
        	$this->config->compmode = 1;
        	$this->config->showwptags = 1;
        	$this->config->mode = 'tags'; 
        	$this->config->tagcloud = '<a href=\'http://foo\' style=\'12\'>Tag 1</a><a href=\'http://foo\' style=\'12\'>Tag 2</a>'; 
        	$this->instance_config_save($this->config);           
        } else {
            if (!empty($this->config->title)){
                $this->title = $this->config->title;
            }            
        }
    }

    function instance_allow_multiple() {
        return true;
    }

    function get_content() {
        if ($this->content !== NULL) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->text = wp_cumulus_createflashcode($this->config);
        $this->content->footer = '';

        return $this->content;
    }

    /*
     * Hide the title bar when none set..
     */
    function hide_header(){
        return empty($this->config->title);
    }
}


// piece together the flash code
function wp_cumulus_createflashcode( $config ){
    global $CFG;

	// get categories
	
	$tagcloud = '';
	$cats = '';
	
	if( $config->mode != "tags" ){
		$cats = 'cat1, cat 2';
	}

	if( $config->mode != "cats" ){
		$tagcloud = $config->tagcloud;
	}
	
	// get some paths
	$movie = $CFG->wwwroot.'/blocks/wp_cumulus/tagcloud.swf';
	$path = $CFG->wwwroot.'/blocks/wp_cumulus/';

	// add random seeds to so name and movie url to avoid collisions and force reloading (needed for IE)
	$soname = 'wpobject'.rand(0,9999999);
	$movie .= '?r=' . rand(0,9999999);
	$divname = 'wp_cumulus'.rand(0,9999999);

	// write flash tag
	if(!$config->compmode){
		$flashtag = '<!-- SWFObject embed by Geoff Stearns geoff@deconcept.com http://blog.deconcept.com/swfobject/ -->';	
		$flashtag .= '<script type="text/javascript" src="'.$path.'swfobject.js"></script>';
		$flashtag .= '<div id="'.$divname.'">';
		if ($config->showwptags) { 
		        $flashtag .= '<p>'; 
        } else { 
                $flashtag .= '<p style="display:none;">'; 
        }

		// alternate content

		$flashtag .= '</p>';
		$flashtag .= '<p>WP Cumulus Flash tag cloud by <a href="http://www.roytanck.com">Roy Tanck</a> and <a href="http://lukemorton.co.uk/">Luke Morton</a> requires <a href="http://www.macromedia.com/go/getflashplayer">Flash Player</a> 9 or better.</p></div>';
		$flashtag .= '<script type="text/javascript">';
		$flashtag .= 'var '.$soname.' = new SWFObject("'.$movie.'", "tagcloudflash", "'.$config->width.'", "'.$config->height.'", "9", "#'.$config->bgcolor.'");';
		if ($config->trans){
			$flashtag .= $soname.".addParam(\"wmode\", \"transparent\");\n";
		}
		$flashtag .= $soname.".addParam(\"allowScriptAccess\", \"always\");\n";
	    $flashtag .= $soname.".addVariable(\"tcolor\", \"0x{$config->tcolor}\");\n";
		$tcolor2 = (empty($config->tcolor2)) ? $config->tcolor : $config->tcolor2;
		$flashtag .= $soname.".addVariable(\"tcolor2\", \"0x{$tcolor2}\");\n";
		$hicolor = (empty($config->hicolor)) ? $config->tcolor : $config->hicolor;
	    $flashtag .= $soname.".addVariable(\"hicolor\", \"0x$hicolor}\");\n";
		$flashtag .= $soname.".addVariable(\"tspeed\", \"{$config->speed}\");\n";
		$flashtag .= $soname.".addVariable(\"distr\", \"{$config->distr}\");\n";
		$flashtag .= $soname.".addVariable(\"mode\", \"{$config->mode}\");\n";
		// put tags in flashvar
		if( $config->mode != "cats" ){
			$flashtag .= $soname.".addVariable(\"tagcloud\", \"<tags>{$tagcloud}</tags>\");\n";
		}
		// put categories in flashvar
		if( $config->mode != "tags" ){
			$flashtag .= $soname.".addVariable(\"categories\", \"{$cats}\");\n";
		}
		$flashtag .= $soname.'.write("'.$divname.'");';
		$flashtag .= '</script>';
		echo htmlentities($flashtag);
	} else {

        $transparency = ($config->trans) ? '<param name="wmode" value="transparent" />' : '';
		$tcolor2 = (empty($config->tcolor2)) ? $config->tcolor : $config->tcolor2;
		$hicolor = (empty($config->hicolor)) ? $config->tcolor : $config->tcolor2;

		$flashvars = "tcolor=0x{$config->tcolor}&amp;tcolor2=0x{$tcolor2}&amp;hicolor=0x{$hicolor}&amp;tspeed={$config->speed}&amp;distr={$config->distr}&amp;mode={$config->mode}";
		// put tags in flashvar
		if( $config->mode != "cats" ){
			$flashvars .= '&amp;tagcloud='.urlencode('<tags>'.$tagcloud.'</tags>');
		}
		// put categories in flashvar
		if( $config->mode != "tags" ){
			$flashvars .= '&amp;categories=' . $cats;
		}

		$flashtag = <<<EOF
		<object type="application/x-shockwave-flash" data="{$movie}" width="{$config->width}" height="{$config->height}">
		    <param name="movie" value="{$movie}" />
		    <param name="bgcolor" value="#{$config->bgcolor}" />
		    <param name="AllowScriptAccess" value="always" />
		    {$transparency}
		    <param name="flashvars" value="{$flashvars}" />
		</object>
EOF;
	}
	return $flashtag;
}

?>