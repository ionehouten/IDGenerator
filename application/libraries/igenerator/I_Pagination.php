<?php

/**
 * Pagination Class
 *
 * @package		CodeIgniter
 * @subpackage          Libraries
 * @category            Pagination
 * @author		EllisLab Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/pagination.html
 */
class I_pagination {

    protected $base_url = '';
    protected $prefix = '';
    protected $suffix = '';
    protected $total_rows = 0;
    protected $num_links = 5; //2
    public $per_page = 10;
    public $cur_page = 0;
    public $num_pages = 0;
    protected $use_page_numbers = TRUE; //false
    protected $first_link = '&lsaquo; First';
    protected $next_link = '&gt;';
    protected $prev_link = '&lt;';
    protected $last_link = 'Last &rsaquo;';
    protected $next_pager_link = '&gt;';
    protected $prev_pager_link = '&lt;';
    protected $uri_segment = 0;
    protected $full_paging_tag_open = '';
    protected $full_paging_tag_close = '';
    protected $col_left_tag_open = '';
    protected $col_left_tag_close = '';
    protected $col_center_tag_open = '';
    protected $col_center_tag_close = '';
    protected $col_right_tag_open = '';
    protected $col_right_tag_close = '';
    protected $full_pager_tag_open = '';
    protected $full_pager_tag_close = '';
    protected $first_tag_open = '';
    protected $first_tag_close = '';
    protected $last_tag_open = '';
    protected $last_tag_close = '';
    protected $first_url = '';
    protected $cur_tag_open = '<strong>';
    protected $cur_tag_close = '</strong>';
    protected $next_tag_open = '';
    protected $next_tag_close = '';
    protected $prev_tag_open = '';
    protected $prev_tag_close = '';
    protected $num_tag_open = '';
    protected $num_tag_close = '';
    protected $disabled_tag_open = '<li class="disabled">';
    protected $disabled_tag_close = '</li>';
    protected $total_tag_open = '';
    protected $total_tag_close = '';
    protected $page_query_string = FALSE;
    protected $query_string_segment = 'per_page';
    protected $display_pages = TRUE;
    protected $_attributes = '';
    protected $_link_types = array();
    protected $reuse_query_string = FALSE;
    protected $use_global_url_suffix = FALSE;
    protected $data_page_attr = 'i_pagination_page';
    protected $cur_page_text = '';
    protected $total_page_text = '';
    protected $total_data_text = '';
    protected $badge_tag_open = '';
    protected $badge_tag_close = '';
    protected $page_size_text = '';
    protected $page_size_tag_open = '';
    protected $page_size_tag_close = '';
    protected $page_size_option = array();
    protected $page_size_attr = array();
    protected $CI;

    // --------------------------------------------------------------------

    /**
     * Constructor
     *
     * @param	array	$params	Initialization parameters
     * @return	void
     */
    public function __construct($params = array()) {
        $this->CI = & get_instance();
        $this->CI->load->language('pagination');
        foreach (array('first_link', 'next_link', 'prev_link', 'last_link') as $key) {
            if (($val = $this->CI->lang->line('pagination_' . $key)) !== FALSE) {
                $this->$key = $val;
            }
        }

        $this->initialize($params);
        log_message('info', 'Pagination Class Initialized');
    }

    // --------------------------------------------------------------------

    /**
     * Initialize Preferences
     *
     * @param	array	$params	Initialization parameters
     * @return	CI_Pagination
     */
    public function initialize(array $params = array()) {
        isset($params['attr']) OR $params['attr'] = array();
        if (is_array($params['attr'])) {
            $this->_parse_attributes($params['attr']);
            unset($params['attr']);
        }

        // Deprecated legacy support for the anchor_class option
        // Should be removed in CI 3.1+
        if (isset($params['anchor_class'])) {
            empty($params['anchor_class']) OR $attributes['class'] = $params['anchor_class'];
            unset($params['anchor_class']);
        }

        foreach ($params as $key => $val) {
            if (property_exists($this, $key)) {
                $this->$key = $val;
            }
        }

        if ($this->CI->config->item('enable_query_strings') === TRUE) {
            $this->page_query_string = TRUE;
        }

        if ($this->use_global_url_suffix === TRUE) {
            $this->suffix = $this->CI->config->item('url_suffix');
        }

        return $this;
    }

    // --------------------------------------------------------------------

    /**
     * Generate the pagination links
     *
     * @return	string
     */
    public function create_links() {
        // If our item count or per-page total is zero there is no need to continue.
        // Note: DO NOT change the operator to === here!
        if ($this->total_rows == 0 OR $this->per_page == 0) {
            return '';
        }

        // Calculate the total number of pages
        $num_pages = (int) ceil($this->total_rows / $this->per_page);
        $this->num_pages = $num_pages;
        
        
        // Is there only one page? Hm... nothing more to do here then.
        if ($num_pages === 1) {
            return '';
        }

        // Check the user defined number of links.
        $this->num_links = (int) $this->num_links;

        if ($this->num_links < 0) {
            show_error('Your number of links must be a non-negative number.');
        }

        // Keep any existing query string items.
        // Note: Has nothing to do with any other query string option.
        if ($this->reuse_query_string === TRUE) {
            $get = $this->CI->input->get();

            // Unset the controll, method, old-school routing options
            unset($get['c'], $get['m'], $get[$this->query_string_segment]);
        } else {
            $get = array();
        }

        // Put together our base and first URLs.
        // Note: DO NOT append to the properties as that would break successive calls
        $base_url = trim($this->base_url);
        $first_url = $this->first_url;

        $query_string = '';
        $query_string_sep = (strpos($base_url, '?') === FALSE) ? '?' : '&amp;';

        // Are we using query strings?
        if ($this->page_query_string === TRUE) {
            // If a custom first_url hasn't been specified, we'll create one from
            // the base_url, but without the page item.
            if ($first_url === '') {
                $first_url = $base_url;

                // If we saved any GET items earlier, make sure they're appended.
                if (!empty($get)) {
                    $first_url .= $query_string_sep . http_build_query($get);
                }
            }

            // Add the page segment to the end of the query string, where the
            // page number will be appended.
            $base_url .= $query_string_sep . http_build_query(array_merge($get, array($this->query_string_segment => '')));
        } else {
            // Standard segment mode.
            // Generate our saved query string to append later after the page number.
            if (!empty($get)) {
                $query_string = $query_string_sep . http_build_query($get);
                $this->suffix .= $query_string;
            }

            // Does the base_url have the query string in it?
            // If we're supposed to save it, remove it so we can append it later.
            if ($this->reuse_query_string === TRUE && ($base_query_pos = strpos($base_url, '?')) !== FALSE) {
                $base_url = substr($base_url, 0, $base_query_pos);
            }

            if ($first_url === '') {
                $first_url = $base_url . $query_string;
            }

            $base_url = rtrim($base_url, '/') . '/';
        }

        // Determine the current page number.
        $base_page = ($this->use_page_numbers) ? 1 : 0;

        // Are we using query strings?
        if ($this->page_query_string === TRUE) {
            $this->cur_page = $this->CI->input->get($this->query_string_segment);
        } else {
            // Default to the last segment number if one hasn't been defined.
            if ($this->uri_segment === 0) {
                $this->uri_segment = count($this->CI->uri->segment_array());
            }

            $this->cur_page = $this->CI->uri->segment($this->uri_segment);

            // Remove any specified prefix/suffix from the segment.
            if ($this->prefix !== '' OR $this->suffix !== '') {
                $this->cur_page = str_replace(array($this->prefix, $this->suffix), '', $this->cur_page);
            }
        }

        // If something isn't quite right, back to the default base page.
        if (!ctype_digit($this->cur_page) OR ( $this->use_page_numbers && (int) $this->cur_page === 0)) {
            $this->cur_page = $base_page;
        } else {
            // Make sure we're using integers for comparisons later.
            $this->cur_page = (int) $this->cur_page;
        }

        // Is the page number beyond the result range?
        // If so, we show the last page.
        if ($this->use_page_numbers) {
            if ($this->cur_page > $num_pages) {
                $this->cur_page = $num_pages;
            }
        } elseif ($this->cur_page > $this->total_rows) {
            $this->cur_page = ($num_pages - 1) * $this->per_page;
        }

        $uri_page_number = $this->cur_page;

        // If we're using offset instead of page numbers, convert it
        // to a page number, so we can generate the surrounding number links.
        if (!$this->use_page_numbers) {
            $this->cur_page = (int) floor(($this->cur_page / $this->per_page) + 1);
        }

        // Calculate the start and end numbers. These determine
        // which number to start and end the digit links with.
        $start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
        $end = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;

        // And here we go...
        $paging = '';
        $pager = '';
        $page_info = '';
        $page_size = '';
        $output = '';
        

        // Render the "First" link.
        if ($this->first_link !== FALSE && $this->cur_page > ($this->num_links + 1 + !$this->num_links)) {
            $attributes = sprintf('%s %s="%d"', $this->_attributes, $this->data_page_attr, 1);
            $paging .= $this->first_tag_open . '<a href="' . $base_url . $this->prefix . '1' . $this->suffix . '"' . $attributes
                    . $this->_attr_rel('start') . '>' . $this->first_link . '</a>' . $this->first_tag_close;
        } else {
            $attributes = sprintf('%s %s="%d"', $this->_attributes, $this->data_page_attr, 1);
            $paging .= $this->disabled_tag_open . '<a href="' . $first_url . '"' . $attributes . $this->_attr_rel('start') . '>'
                    . $this->first_link . '</a>' . $this->disabled_tag_close;
        }
        
        

        // Render the "Previous" link.
        if ($this->prev_link !== FALSE && $this->cur_page !== 1) {
            $i = ($this->use_page_numbers) ? $uri_page_number - 1 : $uri_page_number - $this->per_page;
            if ($i == 0) {
                $i = 1;
            }
            $attributes = sprintf('%s %s="%d"', $this->_attributes, $this->data_page_attr, (int) $i);

            $append = $this->prefix . $i . $this->suffix;
            $paging .= $this->prev_tag_open . '<a href="' . $base_url . $append . '"' . $attributes . $this->_attr_rel('prev') . '>'
                    . $this->prev_link . '</a>' . $this->prev_tag_close;
            
            $pager .= $this->prev_tag_open . '<a href="' . $base_url . $append . '"' . $attributes . $this->_attr_rel('prev') . '>'
                    . $this->prev_pager_link . '</a>' . $this->prev_tag_close;
        }else {
            $i = ($this->use_page_numbers) ? $uri_page_number - 1 : $uri_page_number - $this->per_page;
            if ($i == 0) {
                $i = 1;
            }

            $attributes = sprintf('%s %s="%d"', $this->_attributes, $this->data_page_attr, (int) $i);

            $append = $this->prefix . $i . $this->suffix;
            $paging .= $this->disabled_tag_open . '<a href="' . $base_url . $append . '"' . $attributes . $this->_attr_rel('prev') . '>'
                    . $this->prev_link . '</a>' . $this->disabled_tag_close;
            $pager .= $this->disabled_tag_open . '<a href="' . $base_url . $append . '"' . $attributes . $this->_attr_rel('prev') . '>'
                    . $this->prev_pager_link . '</a>' . $this->disabled_tag_close;
        }


        // Render the pages
        if ($this->display_pages !== FALSE) {
            // Write the digit links
            for ($loop = $start - 1; $loop <= $end; $loop++) {
                $i = ($this->use_page_numbers) ? $loop : ($loop * $this->per_page) - $this->per_page;

                $attributes = sprintf('%s %s="%d"', $this->_attributes, $this->data_page_attr, (int) $i);

                if ($i >= $base_page) {
                    if ($this->cur_page === $loop) {
                        // Current page
                        $paging .= $this->cur_tag_open . $loop . $this->cur_tag_close;
                    } else {
                        $append = $this->prefix . $i . $this->suffix;
                        $paging .= $this->num_tag_open . '<a href="' . $base_url . $append . '"' . $attributes . $this->_attr_rel('start') . '>'
                                . $loop . '</a>' . $this->num_tag_close;
                    }
                }
            }
        }

        // Render the "next" link
        if ($this->next_link !== FALSE && $this->cur_page < $num_pages) {
            $i = ($this->use_page_numbers) ? $this->cur_page + 1 : $this->cur_page * $this->per_page;
            $attributes = sprintf('%s %s="%d"', $this->_attributes, $this->data_page_attr, (int) $i);
            $paging .= $this->next_tag_open . '<a href="' . $base_url . $this->prefix . $i . $this->suffix . '"' . $attributes
                    . $this->_attr_rel('next') . '>' . $this->next_link . '</a>' . $this->next_tag_close;
            $pager .= $this->next_tag_open . '<a href="' . $base_url . $this->prefix . $i . $this->suffix . '"' . $attributes
                    . $this->_attr_rel('next') . '>' . $this->next_pager_link . '</a>' . $this->next_tag_close;
        } else {
            $i = ($this->use_page_numbers) ? $this->cur_page + 1 : $this->cur_page * $this->per_page;
            $attributes = sprintf('%s %s="%d"', $this->_attributes, $this->data_page_attr, (int) $i);
            $paging .= $this->disabled_tag_open . '<a href="' . $base_url . $this->prefix . $i . $this->suffix . '"' . $attributes
                    . $this->_attr_rel('next') . '>' . $this->next_link . '</a>' . $this->disabled_tag_close;
            $pager .= $this->disabled_tag_open . '<a href="' . $base_url . $this->prefix . $i . $this->suffix . '"' . $attributes
                    . $this->_attr_rel('next') . '>' . $this->next_pager_link . '</a>' . $this->disabled_tag_close;
        }

        // Render the "Last" link
        if ($this->last_link !== FALSE && ($this->cur_page + $this->num_links + !$this->num_links) < $num_pages) {
            $i = ($this->use_page_numbers) ? $num_pages : ($num_pages * $this->per_page) - $this->per_page;
            $attributes = sprintf('%s %s="%d"', $this->_attributes, $this->data_page_attr, (int) $i);
            $paging .= $this->last_tag_open . '<a href="' . $base_url . $this->prefix . $i . $this->suffix . '"' . $attributes . '>'
                    . $this->last_link . '</a>' . $this->last_tag_close;
        } else {
            $i = ($this->use_page_numbers) ? $num_pages : ($num_pages * $this->per_page) - $this->per_page;
            $attributes = sprintf('%s %s="%d"', $this->_attributes, $this->data_page_attr, (int) $i);
            $paging .= $this->disabled_tag_open . '<a href="' . $base_url . $this->prefix . $i . $this->suffix . '"' . $attributes . '>'
                    . $this->last_link . '</a>' . $this->disabled_tag_close;
        }


        // Kill double slashes. Note: Sometimes we can end up with a double slash
        // in the penultimate link so we'll kill all double slashes.
        $paging = preg_replace('#([^:"])//+#', '\\1/', $paging);

        
        $page_info .= $this->total_tag_open.$this->cur_page_text . ' ' . $this->cur_page.' ' 
                . $this->total_page_text . ' ' . $num_pages.' ' 
                . $this->badge_tag_open . $this->total_rows . ' ' . $this->total_data_text . '' . $this->badge_tag_close.$this->total_tag_close;
        
        $pager = $this->full_pager_tag_open . $pager . $this->full_pager_tag_close;
        $paging = $this->full_paging_tag_open . $paging . $this->full_paging_tag_close;
        
        
        $page_size_attr = "";
        foreach ($this->page_size_attr as $key => $val) {
            $page_size_attr .= $key . '="' . $val . '" ';
        }
        $page_size .= $this->page_size_tag_open;
        $page_size .= '<select ' . $page_size_attr . '>';
        
        if(!in_array($this->per_page, $this->page_size_option)){
            $this->page_size_option[$this->per_page] = $this->per_page;
            ksort($this->page_size_option);
        }
        foreach ($this->page_size_option as $key => $val) {
            $selected = ($key == $this->per_page) ? 'selected="selected"' : '';
            $page_size .= '<option value="' . $key . '" ' . $selected . '>' . $val . '</option>';
        }
        $page_size .= '</select>';
        $page_size .= $this->page_size_tag_close;

        
        $output .= $this->col_left_tag_open.$page_info.  $this->col_left_tag_close;
        $output .= $this->col_center_tag_open.$paging.$pager.  $this->col_center_tag_close;
        $output .= $this->col_right_tag_open.$page_size.  $this->col_right_tag_close;
        $output .= '<div class="clearfix"></div>';
        
        

        
        return $output;
    }

    // --------------------------------------------------------------------

    /**
     * Parse attributes
     *
     * @param	array	$attributes
     * @return	void
     */
    protected function _parse_attributes($attributes) {
        isset($attributes['rel']) OR $attributes['rel'] = TRUE;
        $this->_link_types = ($attributes['rel']) ? array('start' => 'start', 'prev' => 'prev', 'next' => 'next') : array();
        unset($attributes['rel']);

        $this->_attributes = '';
        foreach ($attributes as $key => $value) {
            $this->_attributes .= ' ' . $key . '="' . $value . '"';
        }
    }

    // --------------------------------------------------------------------

    /**
     * Add "rel" attribute
     *
     * @link	http://www.w3.org/TR/html5/links.html#linkTypes
     * @param	string	$type
     * @return	string
     */
    protected function _attr_rel($type) {
        if (isset($this->_link_types[$type])) {
            unset($this->_link_types[$type]);
            return ' rel="' . $type . '"';
        }

        return '';
    }

}
