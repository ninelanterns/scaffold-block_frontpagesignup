<?php

/**
 * File description
 *
 * @package
 * @subpackage
 * @copyright  &copy; 2012 Nine Lanterns Pty Ltd  {@link http://www.ninelanterns.com.au}
 * @author     tri.le
 * @version    1.0
 */

class block_frontpagesignup extends block_base {

    function init() {
        $this->title = get_string('pluginname', 'block_frontpagesignup');
    }

    function applicable_formats() {
        return array('site' => true);
    }

    public function specialization() {
        $this->title = ''; // title is not displayed for this block
    }

    public function get_content() {
        global $SESSION, $CFG, $OUTPUT;

        if ($this->content !=null) {
            return $this->content;
        }

        $this->content = new stdClass();
        if (!empty($CFG->registerauth) or is_enabled_auth('none') or !empty($CFG->auth_instructions)) { //only display if self sign up is allowed
            $firsttime = get_string("firsttime");
            $html = "<h2>$firsttime</h2>";
            $html .= '<div class="subcontent">';
            if (is_enabled_auth('none')) { // for security reason - should never happen
                $html .= get_string("loginstepsnone");
            } else if ($CFG->registerauth == 'redcross' || $CFG->registerauth == 'cas') { // specifically for redcross login
                $signupurl = '/auth/redcross/signup.php';
                if (!empty($CFG->auth_instructions)) {
                    $html .= format_text($CFG->auth_instructions);
                } else {
                    $html .= get_string('loginsteps', 'block_frontpagesignup', $signupurl);
                }
                $startsignup = get_string('startsignup');
                $html .= "<div class='signupform'>
                   <form action='$signupurl' method='get' id='signup'>
                   <div><input type='submit' value='$startsignup' /></div>
                   </form>
                 </div>
                <div id='extraformatting'>";
                $html .= get_string('needhelp', 'block_frontpagesignup');
                $html .= '</div>';
            } else if (!empty($CFG->registerauth)) {
                $signupurl = '/login/signup.php';
                $startsignup = get_string('startsignup');
                $html .= format_text($CFG->auth_instructions);
                $html .= "<div class='signupform'>
                        <form action='$signupurl' method='get' id='signup'>
                        <div><input type='submit' value='$startsignup' /></div>
                        </form>
                        </div>";
            }
            $html .= '</div>';
            $this->content->text = $html;
        } else {
            $this->content->text = '';
        }

        return $this->content;
    }

}