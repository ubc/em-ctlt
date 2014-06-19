<?php

/*
 * UBC Departments Organization
 * Description: Retrieves UBC departments with their faculty, campus, start date, and and date
 * Author: Nathan Sidles, CTLT
 * Version: 0.1
 * Author URI: http://ctlt.ubc.ca
 */

class ubcdepartments {

    /**
     * Constructs the function and returns formatted data if the formatted
     */
    public function __construct() {
        $this->retrieve_data( );
    }
    
    /**
     * Retrieves the data from the CSV files. Can be set by user; if not set, uses default address.
     */
    function retrieve_data( ) {
    
        ini_set("auto_detect_line_endings", true);

        if( isset( $_GET['dates_url'] ) ) {
            $ubc_units_dates = fopen( $_GET['dates_url'], "r" );
        } else {
            $ubc_units_dates = fopen( "http://em.ctlt.ubc.ca/ps_dept_tbl.csv", "r" );
        }
        
        if( isset( $_GET['organization_url'] ) ) {
              $ubc_units_organization = fopen( $_GET['organization_url'], "r");
        } else {
              $ubc_units_organization = fopen( "http://em.ctlt.ubc.ca/ps_ubc_findnode.csv", "r" );
        }
        
        $data = $this->parse_data_to_array( $ubc_units_dates, $ubc_units_organization );
        
        
        if( isset( $_GET['type'] ) && $_GET['type'] == 'json' ) {
            $this->export_to_json( $data );
        } else if( isset( $_GET['type'] ) && $_GET['type'] == 'xml' ) {
            $this->export_to_xml( $data );
        } else {
            $this->export_as_serialized( $data );
        }
        
        fclose( $ubc_units_dates );
        fclose( $ubc_units_organization );
    }
    
    /**
     * Parses the data from the CSV files, constructs an array. If active or inactive are set, removes them from the constructed array
     * Parameters: Date and organization CSV files
     */
    private function parse_data_to_array( $dates_file, $organization_file ) {
    
        $faculty = '';
        $department = '';
        $active = '';
    
        if( isset( $_GET['location'] ) ) {
            if (strtolower(urldecode($_GET['location'])) == 'vancouver')
                $location = 'UBC - Vancouver';
            if (strtolower(urldecode($_GET['location'])) == 'okanagan')
                $location = 'UBC - Okanagan';
        }
        
        if( isset( $_GET['faculty'] ) ) {
            $faculty = strtolower(urldecode($_GET['faculty']));
        }
        
        if( isset( $_GET['department'] ) ) {
            $department = strtolower(urldecode($_GET['department']));
        }
        
        if( isset( $_GET['active'] ) ) {
            $active = strtolower(urldecode($_GET['active']));
        }

    
        if( $dates_file != null && $organization_file != null ) {
            while ( ($data = fgetcsv( $dates_file ) ) !== FALSE) {
                if( $data[3] == 'A' ) {
                    $array_dates[$data[1]]['id'] = $data[1];
                    $array_dates[$data[1]]['activation_date'] = $data[2];
                    $array_dates[$data[1]]['inactivation_date'] = null;
                }
                if( $data[3] == 'I' ) {
                    $array_dates[$data[1]]['inactivation_date'] = $data[2];
                }
            }
            
            while ( ($data = fgetcsv( $organization_file ) ) !== FALSE) {
                if( ( $data[1] && (isset($_GET['location']) ? ($location == $data[18]) : TRUE) && (isset($_GET['faculty']) ? ($faculty == strtolower($data[16])) : TRUE) && (isset($_GET['department']) ? ($department == strtolower($data[15])) : TRUE) ) ) {
                
                    $array_organization[$data[18]][$data[16]][$data[1]]['id'] = $data[1];
                    $array_organization[$data[18]][$data[16]][$data[1]]['name'] = $data[15];
                    if( !isset( $array_dates[$data[1]]['activation_date'] ) ) {
                        $array_organization[$data[18]][$data[16]][$data[1]]['activation_date'] = $array_dates[$data[1]]['inactivation_date'];
                    } else {
                        $array_organization[$data[18]][$data[16]][$data[1]]['activation_date'] = $array_dates[$data[1]]['activation_date'];
                    }
                    $array_organization[$data[18]][$data[16]][$data[1]]['inactivation_date'] = $array_dates[$data[1]]['inactivation_date'];
                }
                
                
                if( $active == 'active' ) {
                    if( $array_organization[$data[18]][$data[16]][$data[1]]['inactivation_date'] != '' ) {
                        unset($array_organization[$data[18]][$data[16]][$data[1]]);
                    }
                }
                if( $active == 'inactive' ) {
                    if( $array_organization[$data[18]][$data[16]][$data[1]]['inactivation_date'] == '' ) {
                        unset($array_organization[$data[18]][$data[16]][$data[1]]);
                    }
                }
                
            }
            return $array_organization;
        }
    }
    
    /**
     * Exports data as a serialized array
     * Parameter: the array parsed from the CSV files
     */
    private function export_as_serialized( $array_organization ) {
        echo serialize( $array_organization );
    }
    
    /**
     * Exports data as XML
     * Parameter: the array parsed from the CSV files
     */
    private function export_to_xml( $array_organization ) {
        $xml = new SimpleXMLElement("<?xml version=\"1.0\"?><ubc_info></ubc_info>");
        foreach( $array_organization as $organization_key => $organization_value ) {
            $organization_element = $xml->addChild('organization');
            $organization_element->addChild('name', htmlspecialchars($organization_key));
            $faculties = $organization_element->addChild('faculties');
            foreach( $organization_value as $faculty_key => $faculty_value ) {
                $faculty_element = $faculties->addChild('faculty');
                $faculty_element->addChild('name', htmlspecialchars($faculty_key));
                $departments = $faculty_element->addChild('departments');
                foreach( $faculty_value as $department_key => $department_value ) {
                    $department_element = $departments->addChild('department');
                    $department_element->addChild('id', htmlspecialchars($department_value['id']));
                    $department_element->addChild('name', htmlspecialchars($department_value['name']));
                    $department_element->addChild('activation_date', htmlspecialchars($department_value['activation_date']));
                    $department_element->addChild('inactivation_date', htmlspecialchars($department_value['inactivation_date']));
                }
            }
        }

        header('Content-type: text/xml');
        print $xml->asXML();
        $xml->asXML('test2.xml');
    }

    /**
     * Exports data as a JSON object
     * Parameter: the array parsed from the CSV files
     */
    private function export_to_json( $array_organization ) {
    
        $organization_json = json_encode( $array_organization );
    
        if( isset($_GET['callback'] ) ) {
            $organization_json = "( typeof hello === 'function' ) ? hello(" . $organization_json . ") : (function(){ return " . $organization_json . "} )";
        }
    
        echo $organization_json;
    }
    
}

new ubcdepartments();

?>
