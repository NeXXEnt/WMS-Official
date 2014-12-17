<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bins extends CI_Model {

    public $insertIsBuilt = FALSE;
    public $is_init       = FALSE;

	function __construct() {
        parent::__construct();      
    }

    public function build($bin_id, &$message = NULL) {
        $this->db->where('bin_id', $bin_id);
        $query = $this->db->get('Bins');
        if($query->num_rows() == 0) {$message = 'Bin not found';                             return FALSE;}
        if($query->num_rows() > 1)  {$message = 'DB Error, multiple bins with that ID found';return FALSE;}
        if($query->num_rows() == 1) {
            foreach($query->row() as $key => $value) 
                $this->{$key} = $value;
        }
        if($this->user_id) {
            $this->user = new User;
            if(! $this->user->build($this->user_id, $message)){$message = 'Error building user';   return FALSE;}
        }
        $this->warehouse = new Warehouse;
        if(! $this->warehouse->build($this->warehouse_id, $message)) {$message='Error building warehouse';return FALSE;}
        $this->is_init = TRUE;
                                                                                             return TRUE;
    }

    function find_id($warehouse_id, $binAddress) {
        $this->db->where('warehouse_id', $warehouse_id);
        $this->db->where('binAddress', $binAddress);
        $query = $this->db->get('Bins');
        if($query->num_rows() == 0) return FALSE;
        if($query->num_rows() == 1) return $query->row()->bin_id;
    }

    public static function get_infinite_bins($id) {
        $CI =& get_instance();
        $CI->db->where('warehouse_id', $id);
        $CI->db->where('binIsInfinite', TRUE);
        $query = $CI->db->get('Bins');
        return $query->result();

    }

    public static function items_in_user_basket($warehouse_id, $user_id) {
        $items = array();
        $CI =& get_instance();
        $CI->db->where('binIsAUserBasket', TRUE);
        $CI->db->where('warehouse_id', $warehouse_id);
        $CI->db->where('user_id', $user_id);
        $query = $CI->db->get('Bins');
        foreach($query->result() as $row) {
            $CI->db->where('bin_id', $row->bin_id);
            $query2 = $CI->db->get('view_ShippingItemsLong');
            foreach($query2->result() as $row2) {
                $items[$row2->item_id] = $row2;
            }
        }
        return $items;
    }


    public function build_flat($bin_id) {

    }

    public function generate_warehouse_table($warehouse_id, $headers = NULL) {
        
        $query = $this->query_warehouse_rows($warehouse_id);
        if(!$query) return FALSE;

        foreach($query->result() as $row){
            $numLevels = $this->count($warehouse_id, $row->x);            
            $numBins = 1; //$this->count_bins($warehouse_id, $row->x);
            $maxDepth = $this->find_max_depth($warehouse_id, $row->x);
            $emptyBins = 1; //$this->count_empty_bins($warehouse_id, $row->x);
            $return[$row->x] = array($numLevels, $numBins, $maxDepth, $emptyBins);
        }

        /*foreach($return as $row => $data) {
            echo $row;
            foreach($data as $field)
                echo ' -|- '.$field;
            echo '<br>';
        }*/


        $this->db->flush_cache();
        return $return;
    }

    public function query_warehouse_rows($warehouse_id) {
        $this->db->where('warehouse_id', $warehouse_id);
        $this->db->select('x')->distinct();
        $this->db->order_by('x');
        $query = $this->db->get('Bins');
        if(! $query->num_rows()) return FALSE;
        else return $query;
    }

    public function query_row_levels($warehouse_id, $row) {

    }

    public function count($warehouse_id, $row = NULL, $level = NULL) {
        $this->db->where('warehouse_id', $warehouse_id);
        if($row === NULL) $this->db->select('x')->distinct();
        else {
            $this->db->where('x', $row);
            if($level === NULL) $this->db->select('y')->distinct();
            else $this->db->where('y', $level);
        }                    
        $query = $this->db->get('Bins');        
        return $query->num_rows();
    }

    public function count_r($warehouse_id, $row = NULL, $level = NULL) {
        if($level = NULL){

        }
    }

    public function count_empty($warehouse_id, $row = NULL, $level = NULL) {
        $this->db->where('warehouse_id', $warehouse_id);
        if($row) $this->db->where('x', $row);
        if($level) $this->db->where('y', $level);
        $query = $this->db->get('Bins');
        $numBunks = $query->num_rows();
        $emptyBunks = 0;
        foreach($lquery->result() as $bin){                
            $this->db->where('bin_id', $bin->bin_id);
            $mquery = $this->db->get('Items');
            if(!$mquery->num_rows()) $emptyBunks++;
        }
    }

    public function find_max_depth($warehouse_id, $row = NULL, $level = NULL) {
        $this->db->where('warehouse_id', $warehouse_id);
        if($row) $this->db->where('x', $row);
        if($level) $this->db->where('y', $level);
        $this->db->select_max('BinDim.binMaxDepth')->distinct();
        $this->db->select('Bins.bin_dim_id');
        $this->db->join('BinDim', 'Bins.bin_dim_id = BinDim.bin_dim_id');
        $query = $this->db->get('Bins');
        if(!$query->num_rows()) return FALSE;
        else {
            $row = $query->row();
            return $row->binMaxDepth;
        }
    }
    public function build_from_array($bin, &$message) {
        //database table ID's
        if(isset($bin['user_id'])) {
            $this->user_id      = $bin['user_id'];
            $this->user         = new User;
            $this->user->build_user($this->user_id);
        } else
            $this->user_id      = NULL;
       
        $this->warehouse        = new Warehouse;
        $this->warehouse->build_warehouse($bin['warehouse_id']);
       
        //Basic Coords
        $this->x                = strtoupper($bin['x']);
        $this->y                = strtoupper($bin['y']);
        $this->z                = strtoupper($bin['z']);
        if(! $this->pad_these_coords($message)) return FALSE;
        
        $this->bin_id           = isset($bin['bin_id'])    ? $bin['bin_id']  : NULL;
        $this->bin_dim_id       = $bin['bin_dim_id'];
        $this->warehouse_id     = $bin['warehouse_id'];
        
        //special options
        //bin address should be XYZZ in most cases but in case it's not then use the custom address
        $this->binAddress       = $bin['binAddress'] != '' ? $bin['binAddress'] : $this->x.$this->y.$this->z;
        $this->binIsInfinite    = $bin['binIsInfinite'];
        $this->binIsAUserBasket = $bin['binIsAUserBasket'];
        $this->binComment       = $bin['binComment'];
        $this->is_init          = TRUE;
        return TRUE;
    }

    private function pad_these_coords(&$message = NULL, &$x = NULL, &$y = NULL, &$z = NULL, $warehouse_id = NULL, $length = NULL) {
       
        $message = '';
        $update  = FALSE;
        
        if($x === NULL) {$x = $this->x; $update = TRUE;}
        if($y === NULL) {$y = $this->y; $update = TRUE;}
        if($z === NULL) {$z = $this->z; $update = TRUE;}
        
        $warehouse = $warehouse_id === NULL ? $this->warehouse : new Warehouse;
        if($warehouse_id != NULL) $warehouse->build_warehouse($warehouse_id);


        if($warehouse->lockCoordDims) {
            
            if(strlen($x) > $warehouse->xCoordSize && $warehouse->xCoordSize > '0') $message .= 'X ';
            $length['x'] = $warehouse->xCoordSize;
            
            if(strlen($y) > $warehouse->yCoordSize && $warehouse->yCoordSize > '0') $message .= 'Y ';
            $length['y'] = $warehouse->yCoordSize;

            if(strlen($z) > $warehouse->zCoordSize && $warehouse->zCoordSize > '0') $message .= 'Z ';
            $length['z'] = $warehouse->zCoordSize;

            if($message != '') {
                $message .= 'Coords are outside of the warehouse required Rules.<br>
                            X < '.$warehouse->xCoordSize.'<br>
                            Y < '.$warehouse->yCoordSize.'<br>
                            Z < '.$warehouse->zCoordSize;
                return FALSE;
            }
        }
        if(!isset($length['x'])) $length['x'] = $warehouse->xCoordSize;
        if(!isset($length['y'])) $length['y'] = $warehouse->yCoordSize;
        if(!isset($length['z'])) $length['z'] = $warehouse->zCoordSize;
        // Pad the coords
        $x = ctype_alpha($x) ? $x = str_pad($x, $length['x'], '_', STR_PAD_LEFT) : $x = str_pad($x, $length['x'], '0', STR_PAD_LEFT);
        $y = ctype_alpha($y) ? $y = str_pad($y, $length['y'], '_', STR_PAD_LEFT) : $y = str_pad($y, $length['y'], '0', STR_PAD_LEFT);
        $z = ctype_alpha($z) ? $z = str_pad($z, $length['z'], '_', STR_PAD_LEFT) : $z = str_pad($z, $length['z'], '0', STR_PAD_LEFT);
              
        // This is in case they didn't supply their own variables and
        // they just wanted to pad the object itself
        if($update){$this->x=$x;$this->y=$y;$this->z=$z;}

       return TRUE;
    }

    public function create(&$message) {
        if(! $this->is_init) {
            $message = 'Bin not initialized, must build bin first';
            return FALSE;
        }
        if(! $this->insertIsBuilt) {
            if(! $this->build_insert_array(NULL, $message)) return FALSE;
        }
        
        foreach($this->insert_array as $insert)  
            $this->db->insert('Bins', $insert);
        
        return TRUE;
        
    }

    public function build_insert_array($endBin = NULL, &$message) {
        // SINGLE BIN
        if($endBin === NULL) {
            $insert[] = array(
                //for a single bin we can assume that all processing
                //is done in the build array, no formatting or anything
                //should be required here.
                'x'                => $this->x,
                'y'                => $this->y,
                'z'                => $this->z,
                'user_id'          => $this->user_id, //don't check permissions as it doesn't matter
                'bin_dim_id'       => $this->bin_dim_id,
                'warehouse_id'     => $this->warehouse_id,
                'bin_id'           => $this->bin_id,
                'binAddress'       => $this->binAddress,
                'binIsInfinite'    => $this->binIsInfinite,
                'binIsAUserBasket' => $this->binIsAUserBasket,
                'binComment'       => $this->binComment
                );
            $this->insert_array    = $insert;
            $this->insertIsBuilt   = TRUE;
            if(! $this->check_insert_duplication($message)) return FALSE;                                
            return TRUE;
        // MULTIPLE BINS
        } elseif(is_object($endBin)) {
            $length = array(
                'x' => strlen($endBin->x),
                'y' => strlen($endBin->y),
                'z' => strlen($endBin->z)
                );

            $endBin->x++;
            $endBin->y++;
            $endBin->z++;

            for($i=0,$x=$this->x;$x!==$endBin->x;$i++,$x++) {
                $X = $x;
                for($j=0,$y=$this->y;$y!==$endBin->y;$j++,$y++){
                    $Y = $y;
                    for($k=0,$z=$this->z;$z!==$endBin->z;$k++,$z++){
                        $Z = $z;
                        if(! $this->pad_these_coords($message, $X, $Y, $Z, NULL, $length)) return FALSE;
                        if($i > 1000) {$message='To many Rows at a time (over 1,000), try a few less';  return FALSE;}
                        if($j > 1000) {$message='To many Levels at a time (over 1,000), try a few less';return FALSE;}
                        if($k > 1000) {$message='To many Bins at a time (over 1,000), try a few less';  return FALSE;}
                        $insert[] = array(
                            'x'                => $X,
                            'y'                => $Y,
                            'z'                => $Z,
                            'user_id'          => $this->user_id,
                            'bin_dim_id'       => $this->bin_dim_id,
                            'warehouse_id'     => $this->warehouse_id,
                            'bin_id'           => NULL,
                            'binAddress'       => $X.$Y.$Z,
                            'binIsInfinite'    => $this->binIsInfinite,
                            'binIsAUserBasket' => $this->binIsAUserBasket,
                            'binComment'       => $this->binComment
                            );
                    } 
                }
            }
            $this->insert_array                = $insert;                                                  
            $this->insertIsBuilt               = TRUE;
            if(! $this->check_insert_duplication($message)) return FALSE;
            return true;

        } else {
            $message = 'Unsupported data type used in build_insert_array()';
            return FALSE;
        }
    }

    public function diffCoord($from, $to) {

        if(ctype_digit($from) && ctype_digit($to)) return ($to - $from);
        
        $from = array_reverse(str_split($from));
        $to   = array_reverse(str_split($to));

        $j = 0;
        foreach($to as $key => $char) {
            $i = 0;
            if(isset($from[$key])) {
                
                while($from[$key] < $char) {
                    
                    $from[$key]++;
                    $i++;
                }

            } else {

                $i=26;
            }

           $j= $j + ($i*(exp($key*log(26))));

        }
        return $j;
    }


    public function are_compatible_with($compare, &$message) {
        if(!$this->is_init || !$compare->is_init) {
            $message = 'Bin object not initialized';
            return FALSE;    
        }

        $X = FALSE;
        $Y = FALSE;
        $Z = FALSE;
        
        if(ctype_digit($this->x) && ctype_digit($compare->x) && $this->x <= $compare->x) $X = TRUE;
        if(ctype_digit($this->y) && ctype_digit($compare->y) && $this->y <= $compare->y) $Y = TRUE;
        if(ctype_digit($this->z) && ctype_digit($compare->z) && $this->z <= $compare->z) $Z = TRUE;

        
        if(ctype_alpha($this->x) && ctype_alpha($compare->x)) {
            $from = array_reverse(str_split($this->x));
            $to   = array_reverse(str_split($compare->x));
            foreach($to as $key => $char) {
                if(isset($from[$key])) {
                   // if(strnatcmp($from[$key], $char)<=0)
                }
            }
            
        }
                                                                                          $X = true;
        if(ctype_alpha($this->y) && ctype_alpha($compare->y)) 
            if(strnatcmp($compare->y, $this->y) >= 0)                                     $Y = TRUE;

        if(ctype_alpha($this->z) && ctype_alpha($compare->z))
            if(strnatcmp($compare->z, $this->z) >= 0)                                     $Z = TRUE; 

        if(!$X) $message = 'X';
        if(!$Y) $message = $message.' Y';
        if(!$Z) $message = $message.' Z';
        if(!$X || !$Y || !$Z) $message = $message.' coordinates are not compatible with each other';


        if($X && $Y && $Z) return TRUE;
        else               return FALSE;
    }

    public function check_insert_duplication(&$message) {
        if(! $this->is_init)           {$message = 'Object Not initialized'; return FALSE;}
        if(! $this->insertIsBuilt)     {$message = 'Insert array Not Built'; return FALSE;}
        $message = '';
        foreach($this->insert_array as $key => $bin) {
            
            $where = '(x = "'.$bin['x'].'" AND y = "'.$bin['y'].'" AND z = "'.$bin['z'].'" AND warehouse_id = "'.$this->warehouse_id.'") OR (binAddress = "'.$bin['binAddress'].'" AND warehouse_id = "'.$this->warehouse_id.'")';
            $this->db->where($where);
            $query = $this->db->get('Bins');
            if($query->num_rows() > '0') {
                $message .= $bin['binAddress'].' or '.$bin['x'].'.'.$bin['y'].'.'.$bin['z'].' already exists<br>'; 
                unset($this->insert_array[$key]);
            }
            
        }

        if($message !== '') return FALSE;
        else return TRUE;


    }

    public function rm_bin(&$message = NULL, $range = NULL) {
        
        if(is_array($range)){
            if($range['endXCoord'] == '') $range['endXCoord'] = $range['startXCoord'];
            if($range['endYCoord'] == '') $range['endYCoord'] = $range['startYCoord'];
            if($range['endZCoord'] == '') $range['endYCoord'] = $range['startZCoord'];

            $startX       = $range['startXCoord'];
            $startY       = $range['startYCoord'];
            $startZ       = $range['startZCoord'];
            
            $endX         = $range['endXCoord']++;
            $endY         = $range['endYCoord']++;
            $endZ         = $range['endZCoord']++;
            
            $warehouse_id = $range['warehouse_id'];

            $this->db->where('warehouse_id', $warehouse_id);

            $this->db->where('x >=', $startX);
            $this->db->where('x <=', $endX);

            if($startY !== '') $this->db->where('y >=', $startY);
            if($startY !== '') $this->db->where('z <=', $endY);

            if($startZ !== '') $this->db->where('z >=', $startZ);
            if($startZ !== '') $this->db->where('z <=', $endZ);

            $query = $this->db->get('Bins');
            if($query->num_rows == 0) {$message='No Bins Found'; return FALSE;}
            foreach($query->result() as $row) {
                $bin = new Bins;
                if(! $bin->build($row->bin_id, $message)) return FALSE;
                if(! $bin->rm_bin($message))    return FALSE;
                
            }
            return TRUE;

        } elseif ($range == NULL){
            if(! $this->is_init) {$message = 'Bin not initialized';                                  return FALSE;}
            $this->db->where('bin_id', $this->bin_id)->delete('Bins');
            if($this->db->affected_rows() == 0) {$message = 'Unable to delete Bin';                  return FALSE;}
            if($this->db->affected_rows() > 1)  {$message = 'More than 1 bin deleted, inform admin'; return FALSE;}
            if($this->db->affected_rows() == 1)                                                      return TRUE;
        } else                                                                                       return FALSE;
            
    }
}

// End of file