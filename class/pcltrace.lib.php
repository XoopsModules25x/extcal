<?php
// --------------------------------------------------------------------------------
// PhpConcept Library (PCL) Trace 1.0
// --------------------------------------------------------------------------------
// License GNU/GPL - Vincent Blavet - Janvier 2001
// http://www.phpconcept.net & http://phpconcept.free.fr
// --------------------------------------------------------------------------------
// Français :
//   La description de l'usage de la librairie PCL Trace 1.0 n'est pas encore
//   disponible. Celle-ci n'est pour le moment distribuée qu'avec l'application
//   et la librairie PhpZip.
//   Une version indépendante sera bientot disponible sur http://www.phpconcept.net
//
// English :
//   The PCL Trace 1.0 library description is not available yet. This library is
//   released only with PhpZip application and library.
//   An independant release will be soon available on http://www.phpconcept.net
//
// --------------------------------------------------------------------------------
//
//   * Avertissement :
//
//   Cette librairie a été créée de façon non professionnelle.
//   Son usage est au risque et péril de celui qui l'utilise, en aucun cas l'auteur
//   de ce code ne pourra être tenu pour responsable des éventuels dégats qu'il pourrait
//   engendrer.
//   Il est entendu cependant que l'auteur a réalisé ce code par plaisir et n'y a
//   caché aucun virus, ni malveillance.
//   Cette libairie est distribuée sous la license GNU/GPL (https://www.gnu.org)
//
//   * Auteur :
//
//   Ce code a été écrit par Vincent Blavet (vincent@blavet.net) sur son temps
//   de loisir.
//
// --------------------------------------------------------------------------------

// ----- Look for double include
if (!defined('PCLTRACE_LIB')) {
    define('PCLTRACE_LIB', 1);

    // ----- Version
    $g_pcl_trace_version = '1.0';

    // ----- Internal variables
    // These values must be change by PclTrace library functions
    $g_pcl_trace_mode     = 'memory';
    $g_pcl_trace_filename = 'trace.txt';
    $g_pcl_trace_name     = [];
    $g_pcl_trace_index    = 0;
    $g_pcl_trace_level    = 0;
    //$g_pcl_trace_entries = [];

    // --------------------------------------------------------------------------------
    // Function : TrOn($p_level, $p_mode, $p_filename)
    // Description :
    // Parameters :
    //   $p_level : Trace level
    //   $p_mode : Mode of trace displaying :
    //             'normal' : messages are displayed at function call
    //             'memory' : messages are memorized in a table and can be display by
    //                        TrDisplay() function. (default)
    //             'log'    : messages are writed in the file $p_filename
    // --------------------------------------------------------------------------------
    /**
     * @param int    $p_level
     * @param string $p_mode
     * @param string $p_filename
     */
    function TrOn($p_level = 1, $p_mode = 'memory', $p_filename = 'trace.txt')
    {
        global $g_pcl_trace_level;
        global $g_pcl_trace_mode;
        global $g_pcl_trace_filename;
        global $g_pcl_trace_name;
        global $g_pcl_trace_index;
        global $g_pcl_trace_entries;

        // ----- Enable trace mode
        $g_pcl_trace_level = $p_level;

        // ----- Memorize mode and filename
        switch ($p_mode) {
            case 'normal':
            case 'memory':
            case 'log':
                $g_pcl_trace_mode = $p_mode;
                break;
            default:
                $g_pcl_trace_mode = 'logged';
        }

        // ----- Memorize filename
        $g_pcl_trace_filename = $p_filename;
    }

    // --------------------------------------------------------------------------------

    // --------------------------------------------------------------------------------
    // Function : IsTrOn()
    // Description :
    // Return value :
    //   The trace level (0 for disable).
    // --------------------------------------------------------------------------------
    /**
     * @return int
     */
    function IsTrOn()
    {
        global $g_pcl_trace_level;

        return $g_pcl_trace_level;
    }

    // --------------------------------------------------------------------------------

    // --------------------------------------------------------------------------------
    // Function : TrOff()
    // Description :
    // Parameters :
    // --------------------------------------------------------------------------------
    function TrOff()
    {
        global $g_pcl_trace_level;
        global $g_pcl_trace_mode;
        global $g_pcl_trace_filename;
        global $g_pcl_trace_name;
        global $g_pcl_trace_index;

        // ----- Clean
        $g_pcl_trace_mode = 'memory';
        unset($g_pcl_trace_entries, $g_pcl_trace_name, $g_pcl_trace_index);

        // ----- Switch off trace
        $g_pcl_trace_level = 0;
    }

    // --------------------------------------------------------------------------------

    // --------------------------------------------------------------------------------
    // Function : TrFctStart()
    // Description :
    //   Just a trace function for debbugging purpose before I use a better tool !!!!
    //   Start and stop of this function is by $g_pcl_trace_level global variable.
    // Parameters :
    //   $p_level : Level of trace required.
    // --------------------------------------------------------------------------------
    /**
     * @param        $p_file
     * @param        $p_line
     * @param        $p_name
     * @param string $p_param
     * @param string $p_message
     */
    function TrFctStart($p_file, $p_line, $p_name, $p_param = '', $p_message = '')
    {
        global $g_pcl_trace_level;
        global $g_pcl_trace_mode;
        global $g_pcl_trace_filename;
        global $g_pcl_trace_name;
        global $g_pcl_trace_index;
        global $g_pcl_trace_entries;

        // ----- Look for disabled trace
        if ($g_pcl_trace_level < 1) {
            return;
        }

        // ----- Add the function name in the list
        if (!isset($g_pcl_trace_name)) {
            $g_pcl_trace_name = $p_name;
        } else {
            $g_pcl_trace_name .= ',' . $p_name;
        }

        // ----- Update the function entry
        $i                                  = count($g_pcl_trace_entries);
        $g_pcl_trace_entries[$i]['name']    = $p_name;
        $g_pcl_trace_entries[$i]['param']   = $p_param;
        $g_pcl_trace_entries[$i]['message'] = '';
        $g_pcl_trace_entries[$i]['file']    = $p_file;
        $g_pcl_trace_entries[$i]['line']    = $p_line;
        $g_pcl_trace_entries[$i]['index']   = $g_pcl_trace_index;
        $g_pcl_trace_entries[$i]['type']    = '1'; // means start of function

        // ----- Update the message entry
        if ('' != $p_message) {
            $i                                  = count($g_pcl_trace_entries);
            $g_pcl_trace_entries[$i]['name']    = '';
            $g_pcl_trace_entries[$i]['param']   = '';
            $g_pcl_trace_entries[$i]['message'] = $p_message;
            $g_pcl_trace_entries[$i]['file']    = $p_file;
            $g_pcl_trace_entries[$i]['line']    = $p_line;
            $g_pcl_trace_entries[$i]['index']   = $g_pcl_trace_index;
            $g_pcl_trace_entries[$i]['type']    = '3'; // means message
        }

        // ----- Action depending on mode
        PclTraceAction($g_pcl_trace_entries[$i]);

        // ----- Increment the index
        ++$g_pcl_trace_index;
    }

    // --------------------------------------------------------------------------------

    // --------------------------------------------------------------------------------
    // Function : TrFctEnd()
    // Description :
    //   Just a trace function for debbugging purpose before I use a better tool !!!!
    //   Start and stop of this function is by $g_pcl_trace_level global variable.
    // Parameters :
    //   $p_level : Level of trace required.
    // --------------------------------------------------------------------------------
    /**
     * @param        $p_file
     * @param        $p_line
     * @param int    $p_return
     * @param string $p_message
     */
    function TrFctEnd($p_file, $p_line, $p_return = 1, $p_message = '')
    {
        global $g_pcl_trace_level;
        global $g_pcl_trace_mode;
        global $g_pcl_trace_filename;
        global $g_pcl_trace_name;
        global $g_pcl_trace_index;
        global $g_pcl_trace_entries;

        // ----- Look for disabled trace
        if ($g_pcl_trace_level < 1) {
            return;
        }

        // ----- Extract the function name in the list
        // ----- Remove the function name in the list
        if (!($v_name = mb_strrchr($g_pcl_trace_name, ','))) {
            $v_name           = $g_pcl_trace_name;
            $g_pcl_trace_name = '';
        } else {
            $g_pcl_trace_name = mb_substr($g_pcl_trace_name, 0, -mb_strlen($v_name));
            $v_name           = mb_substr($v_name, -mb_strlen($v_name) + 1);
        }

        // ----- Decrement the index
        --$g_pcl_trace_index;

        // ----- Update the message entry
        if ('' != $p_message) {
            $i                                  = count($g_pcl_trace_entries);
            $g_pcl_trace_entries[$i]['name']    = '';
            $g_pcl_trace_entries[$i]['param']   = '';
            $g_pcl_trace_entries[$i]['message'] = $p_message;
            $g_pcl_trace_entries[$i]['file']    = $p_file;
            $g_pcl_trace_entries[$i]['line']    = $p_line;
            $g_pcl_trace_entries[$i]['index']   = $g_pcl_trace_index;
            $g_pcl_trace_entries[$i]['type']    = '3'; // means message
        }

        // ----- Update the function entry
        $i                                  = count($g_pcl_trace_entries);
        $g_pcl_trace_entries[$i]['name']    = $v_name;
        $g_pcl_trace_entries[$i]['param']   = $p_return;
        $g_pcl_trace_entries[$i]['message'] = '';
        $g_pcl_trace_entries[$i]['file']    = $p_file;
        $g_pcl_trace_entries[$i]['line']    = $p_line;
        $g_pcl_trace_entries[$i]['index']   = $g_pcl_trace_index;
        $g_pcl_trace_entries[$i]['type']    = '2'; // means end of function

        // ----- Action depending on mode
        PclTraceAction($g_pcl_trace_entries[$i]);
    }

    // --------------------------------------------------------------------------------

    // --------------------------------------------------------------------------------
    // Function : TrFctMessage()
    // Description :
    // Parameters :
    // --------------------------------------------------------------------------------
    /**
     * @param        $p_file
     * @param        $p_line
     * @param        $p_level
     * @param string $p_message
     */
    function TrFctMessage($p_file, $p_line, $p_level, $p_message = '')
    {
        global $g_pcl_trace_level;
        global $g_pcl_trace_mode;
        global $g_pcl_trace_filename;
        global $g_pcl_trace_name;
        global $g_pcl_trace_index;
        global $g_pcl_trace_entries;

        // ----- Look for disabled trace
        if ($g_pcl_trace_level < $p_level) {
            return;
        }

        // ----- Update the entry
        $i                                  = count($g_pcl_trace_entries);
        $g_pcl_trace_entries[$i]['name']    = '';
        $g_pcl_trace_entries[$i]['param']   = '';
        $g_pcl_trace_entries[$i]['message'] = $p_message;
        $g_pcl_trace_entries[$i]['file']    = $p_file;
        $g_pcl_trace_entries[$i]['line']    = $p_line;
        $g_pcl_trace_entries[$i]['index']   = $g_pcl_trace_index;
        $g_pcl_trace_entries[$i]['type']    = '3'; // means message of function

        // ----- Action depending on mode
        PclTraceAction($g_pcl_trace_entries[$i]);
    }

    // --------------------------------------------------------------------------------

    // --------------------------------------------------------------------------------
    // Function : TrMessage()
    // Description :
    // Parameters :
    // --------------------------------------------------------------------------------
    /**
     * @param        $p_file
     * @param        $p_line
     * @param        $p_level
     * @param string $p_message
     */
    function TrMessage($p_file, $p_line, $p_level, $p_message = '')
    {
        global $g_pcl_trace_level;
        global $g_pcl_trace_mode;
        global $g_pcl_trace_filename;
        global $g_pcl_trace_name;
        global $g_pcl_trace_index;
        global $g_pcl_trace_entries;

        // ----- Look for disabled trace
        if ($g_pcl_trace_level < $p_level) {
            return;
        }

        // ----- Update the entry
        $i                                  = count($g_pcl_trace_entries);
        $g_pcl_trace_entries[$i]['name']    = '';
        $g_pcl_trace_entries[$i]['param']   = '';
        $g_pcl_trace_entries[$i]['message'] = $p_message;
        $g_pcl_trace_entries[$i]['file']    = $p_file;
        $g_pcl_trace_entries[$i]['line']    = $p_line;
        $g_pcl_trace_entries[$i]['index']   = $g_pcl_trace_index;
        $g_pcl_trace_entries[$i]['type']    = '4'; // means simple message

        // ----- Action depending on mode
        PclTraceAction($g_pcl_trace_entries[$i]);
    }

    // --------------------------------------------------------------------------------

    // --------------------------------------------------------------------------------
    // Function : TrDisplay()
    // Description :
    // Parameters :
    // --------------------------------------------------------------------------------
    function TrDisplay()
    {
        global $g_pcl_trace_level;
        global $g_pcl_trace_mode;
        global $g_pcl_trace_filename;
        global $g_pcl_trace_name;
        global $g_pcl_trace_index;
        global $g_pcl_trace_entries;

        // ----- Look for disabled trace
        if (($g_pcl_trace_level <= 0) || ('memory' !== $g_pcl_trace_mode)) {
            return;
        }

        $v_font = '"Verdana, Arial, Helvetica, sans-serif"';

        // ----- Trace Header
        echo '<table width=100% border=0 cellspacing=0 cellpadding=0>';
        echo '<tr bgcolor=#0000CC>';
        echo '<td bgcolor=#0000CC width=1>';
        echo '</td>';
        echo "<td><div align=center><span style='font-size: small; color: #FFFFFF; font-family: $v_font;'>Trace</span></div></td>";
        echo '</tr>';
        echo '<tr>';
        echo '<td bgcolor=#0000CC width=1>';
        echo '</td>';
        echo '<td>';

        // ----- Content header
        echo '<table width=100% border=0 cellspacing=0 cellpadding=0>';

        // ----- Display
        $v_again = 0;
        for ($i = 0, $iMax = count($g_pcl_trace_entries); $i < $iMax; ++$i) {
            // ---- Row header
            echo '<tr>';
            echo '<td><table width=100% border=0 cellspacing=0 cellpadding=0><tr>';
            $n = ($g_pcl_trace_entries[$i]['index'] + 1) * 10;
            echo '<td width=' . $n . '><table width=100% border=0 cellspacing=0 cellpadding=0><tr>';

            for ($j = 0; $j <= $g_pcl_trace_entries[$i]['index']; ++$j) {
                if ($j == $g_pcl_trace_entries[$i]['index']) {
                    if ((1 == $g_pcl_trace_entries[$i]['type']) || (2 == $g_pcl_trace_entries[$i]['type'])) {
                        echo "<td width=10><div align=center><span style='font-size: x-small; font-family: $v_font; '>+</span></div></td>";
                    }
                } else {
                    echo "<td width=10><span style='font-size: x-small; font-family: $v_font; '>|</span></td>";
                }
            }
            //echo "<td>&nbsp</td>";
            echo '</tr></table></td>';

            echo '<td width=2></td>';
            switch ($g_pcl_trace_entries[$i]['type']) {
                case 1:
                    echo "<td><span style='font-size: x-small; font-family: $v_font; '>" . $g_pcl_trace_entries[$i]['name'] . '(' . $g_pcl_trace_entries[$i]['param'] . ')</span></td>';
                    break;
                case 2:
                    echo "<td><span style='font-size: x-small; font-family: $v_font; '>" . $g_pcl_trace_entries[$i]['name'] . '()=' . $g_pcl_trace_entries[$i]['param'] . '</span></td>';
                    break;
                case 3:
                case 4:
                    echo '<td><table width=100% border=0 cellspacing=0 cellpadding=0><td width=20></td><td>';
                    echo "<span style='font-size: x-small; font-family: $v_font; '>" . $g_pcl_trace_entries[$i]['message'] . '</span>';
                    echo '</td></table></td>';
                    break;
                default:
                    echo "<td><span style='font-size: x-small; font-family: $v_font; '>" . $g_pcl_trace_entries[$i]['name'] . '(' . $g_pcl_trace_entries[$i]['param'] . ')</span></td>';
            }
            echo '</tr></table></td>';
            echo '<td width=5></td>';
            echo '<td><span style="font-size: xx-small; font-family: $v_font; ">' . basename($g_pcl_trace_entries[$i]['file']) . '</span></td>';
            echo '<td width=5></td>';
            echo '<td><span style="font-size: xx-small; font-family: $v_font; ">' . $g_pcl_trace_entries[$i]['line'] . '</span></td>';
            echo '</tr>';
        }

        // ----- Content footer
        echo '</table>';

        // ----- Trace footer
        echo '</td>';
        echo '<td bgcolor=#0000CC width=1>';
        echo '</td>';
        echo '</tr>';
        echo '<tr bgcolor=#0000CC>';
        echo '<td bgcolor=#0000CC width=1>';
        echo '</td>';
        echo "<td><div align=center><span style='color: #FFFFFF; font-family: $v_font;'>&nbsp</span></div></td>";
        echo '</tr>';
        echo '</table>';
    }

    // --------------------------------------------------------------------------------

    // --------------------------------------------------------------------------------
    // Function : PclTraceAction()
    // Description :
    // Parameters :
    // --------------------------------------------------------------------------------
    /**
     * @param $p_entry
     */
    function PclTraceAction($p_entry)
    {
        global $g_pcl_trace_level;
        global $g_pcl_trace_mode;
        global $g_pcl_trace_filename;
        global $g_pcl_trace_name;
        global $g_pcl_trace_index;
        global $g_pcl_trace_entries;

        if ('normal' === $g_pcl_trace_mode) {
            for ($i = 0; $i < $p_entry['index']; ++$i) {
                echo '---';
            }
            if (1 == $p_entry['type']) {
                echo '<b>' . $p_entry['name'] . '</b>(' . $p_entry['param'] . ') : ' . $p_entry['message'] . ' [' . $p_entry['file'] . ', ' . $p_entry['line'] . ']<br>';
            } else if (2 == $p_entry['type']) {
                echo '<b>' . $p_entry['name'] . '</b>()=' . $p_entry['param'] . ' : ' . $p_entry['message'] . ' [' . $p_entry['file'] . ', ' . $p_entry['line'] . ']<br>';
            } else {
                echo $p_entry['message'] . ' [' . $p_entry['file'] . ', ' . $p_entry['line'] . ']<br>';
            }
        }
    }

    // --------------------------------------------------------------------------------

    // ----- End of double include look
}
