<?php //$Id: block_certificates.php,v 1.8.22.8 2009/10/30 23:36:26 poltawski Exp $

class block_my_certificates extends block_list {

    function init() {
        $this->title = get_string('blockname', 'block_my_certificates');
    }

    function applicable_formats() {
        return array('all' => false, 'my' => true, 'course' => true);
    }

    function specialization() {
        if (!empty($this->config->title)) $this->title = format_string($this->config->title);
    }

    function instance_allow_multiple() {
        return true;
    }

    function get_content() {
        global $USER, $CFG, $DB, $OUTPUT;

        if ($this->content !== NULL) {
            return $this->content;
        }

        if (!isset($this->config)) {
            $this->config = new StdClass();
        }

        $context = context_block::instance($this->instance->id);

        if (!empty($this->config->accessbehalfed)) {
            if (!has_capability('block/my_certificates:havebehalfed', $context)) {
                $this->content = new StdClass();
                $this->content->icons = null;
                $this->content->items = null;
                return $this->content;
            }
        }

        $this->content = new stdClass();
        $this->content->icons = array();
        $this->content->items = array();

        if (empty($this->config->accessbehalfed)) {
            // In this case, we get your own certificates
            $sql = "
                SELECT DISTINCT
                    cm.id,
                    cm.instance
                FROM
                    {role_assignments} ra,
                    {context} c,
                    {course_modules} cm,
                    {modules} m
                WHERE
                    ra.contextid = c.id AND
                    c.instanceid = cm.id AND
                    c.contextlevel = ".CONTEXT_USER." AND
                    ra.userid = {$USER->id} AND
                    cm.module = m.id AND
                    m.name = 'certificate'
            ";

            if ($role_assignments = $DB->get_records_sql($sql)) {
                foreach ($role_assignments as $cm) {
                    $url = new moodle_url('/mod/certificate/view.php', array('id' => $cm->id));
                    $certificate = $DB->get_record('certificate', array('id' => $cm->instance));
                    $this->content->icons[] = '<img src="'.$OUTPUT->pîx_url('icon', 'certificate').'" />';
                    $this->content->items[] = '<a href="'.$url.'">'.$certificate->name.'</a>';
                }
            } else {
                $this->content->icons[] = '';
                $this->content->items[] = get_string('nocertificates', 'block_my_certificates');
            }
        } else {
            // I'am watching certificates of my behalfes, not mine
            require_once($CFG->dirroot.'/mod/pdcertificate/xlib.php');
            $mycontext = context_user::instance($USER->id);

            $certstomakestr = get_string('certstomake', 'block_my_certificates');

            $mycourses = enrol_get_my_courses();

            $mybehalfs = get_users_by_capability($mycontext, 'block/my_certificates:isbehalfof', 'u.id, firstname, lastname', 'lastname, firstname');

            if (!empty($mybehalfs)) {
                $myaccessiblecerts = array();

                $certifiablecerts = array();

                foreach ($mybehalfs as $u) {
                    foreach ($mycourses as $cid => $course) {
                        if ($certs = certificate_get_user_certificates($course, $u->id)) {
    
                            // Save that this cert has potential certifiable users
                            foreach ($certs as $cert) {
                                if (!$cert->issued) {
                                    $certifiablecerts[$cert->id] = true;
                                }
                                $myaccessiblecerts[$cert->id] = $cert;
                            }
                        }
                    }
                }

                if (!empty($myaccessiblecerts)) {
                    $attrs = array('class' => 'iconlarge');
                    foreach ($myaccessiblecerts as $cert) {
                        if (array_key_exists($cert->id, $certifiablecerts)) {
                            $this->content->icons[] = $OUTPUT->pix_icon('hoticon', $certstomakestr, 'block_my_certificates', $attrs);
                        } else {
                            $this->content->icons[] = $OUTPUT->pix_icon('icon', '', 'certificate', $attrs);
                        }
                        $reporturl = new moodle_url('/mod/pdcertificate/report.php', array('id' => $cert->cmid));
                        $this->content->items[] = '<a href="'.$reporturl.'">['.$cert->shortname.'] '.$cert->name.'</a>';
                    }
                } else {
                    $this->content->icons[] = '';
                    $this->content->items[] = get_string('nocertificates', 'block_my_certificates');
                }
            } else {
                $this->content->icons[] = '';
                $this->content->items[] = get_string('nobehalves', 'block_my_certificates');
            }
        }

        $this->content->footer = '';

        return $this->content;
    }

    /*
     * Hide the title bar when none set..
     */
    function hide_header() {
        return empty($this->config->title);
    }
}
