<?php

namespace Galette\IO;

use Analog\Analog;
use Galette\Entity\Adherent;
use Galette\Entity\ImportModel;
use Galette\Entity\FieldsConfig;
use Galette\IO\FileTrait;

/**
 * CSV imports AAE
 */

class CsvAAE extends Csv implements FileInterface
{
    use FileTrait;

    const DEFAULT_DIRECTORY = GALETTE_IMPORTS_PATH;
    const DATA_IMPORT_ERROR = -10;

    protected $extensions = array('csv', 'txt');

    private $_fields;
    private $_default_fields = array(
        'nom_adh',
        'prenom_adh',
        'ddn_adh',
        'email_adh'
        #'formation',
        #'annee_debut',
        #'annee_fin'
        #'login_adh',
        #'id_statut'
    );

    private $_dryrun = true;

    private $_members_fields;
    private $_required;

    /**
     * Default constructor
     */
    public function __construct()
    {
        $this->init(
            self::DEFAULT_DIRECTORY,
            $this->extensions,
            array(
                'csv'    =>    'text/csv',
                'txt'    =>    'text/plain'
            ),
            2048
        );

        parent::__construct(self::DEFAULT_DIRECTORY);
    }

    /**
     * Load fields list from database or from default values
     *
     * @return void
     */
    private function _loadFields()
    {
        //at last, we got the defaults
        $this->_fields = $this->_default_fields;

 
        $model = new ImportModel();
        //we go with default fields if model cannot be loaded
        if ( $model->load() ) {
            $this->_fields = $model->getFields();
        }

    }

    /**
     * Get default fields
     *
     * @return array
     */
    public function getDefaultFields()
    {
        return $this->_default_fields;
    }

    /**
     * Import members from CSV file
     *
     * @param string  $filename       CSV filename
     * @param array   $members_fields Members fields
     * @param boolean $dryrun         Run in dry run mode (do not store in database)
     *
     * @return boolean
     */
    public function import($filename, $members_fields, $dryrun, $formation, $annee_debut,$annee_fin)
    {
        if ( !file_exists(self::DEFAULT_DIRECTORY . '/' . $filename)
            || !is_readable(self::DEFAULT_DIRECTORY . '/' . $filename)
        ) {
            Analog::log(
                'File ' . $filename . ' does not exists or cannot be read.',
                Analog::ERROR
            );
            return false;
        }

        if ( $dryrun === false ) {
            $this->_dryrun = false;
        }

        $this->_loadFields();
        $this->_members_fields = $members_fields;
        $this->_formation = $formation;
        $this->_annee_debut = $annee_debut;
        $this->_annee_fin = $annee_fin;

        if ( !$this->_check($filename) ) {
            return self::INVALID_FILE;
        }

        if ( !$this->_storeMembers($filename) ) {
            return self::DATA_IMPORT_ERROR;
        }

        return true;
    }

    /**
     * Check if input file meet requirements
     *
     * @param string $filename File name
     *
     * @return boolean
     */
    private function _check($filename)
    {
        //deal with mac e-o-l encoding -- see if needed
        //@ini_set('auto_detect_line_endings', true);
        $handle = fopen(self::DEFAULT_DIRECTORY . '/' . $filename, 'r');
        if (! $handle) {
            Analog::log(
                'File ' . $filename . ' cannot be open!',
                Analog::ERROR
            );
            $this->addError(
                str_replace(
                    '%filename',
                    $filename,
                    _T('File %filename cannot be open!')
                )
            );
            return false;
        }

        if ( $handle !== false ) {

            $cnt_fields = count($this->_fields);

            //check required fields
            $fc = new FieldsConfig(Adherent::TABLE, $this->_members_fields);
            $config_required = $fc->getRequired();
            $this->_required = array();

            foreach ( array_keys($config_required) as $field ) {
                if ( in_array($field, $this->_fields) ) {
                    $this->_required[$field] = $field;
                }
            }

            $row = 0;
            while ( ($data = fgetcsv(
                $handle,
                1000,
                self::DEFAULT_SEPARATOR,
                self::DEFAULT_QUOTE
            )) !== false) {

                //check fields count
                $count = count($data);
                if ( $count != $cnt_fields ) {
                    $this->addError(
                        str_replace(
                            array('%should_count', '%count', '%row'),
                            array($cnt_fields, $count, $row),
                            _T("Fields count mismatch... There should be %should_count fields and there are %count (row %row)")
                        )
                    );
                    return false;
                }

                //check required fields
                if ( $row > 0 ) {
                    //header line is the first one. Here comes data
                    $col = 0;
                    foreach ( $data as $column ) {
                        if ( in_array($this->_fields[$col], $this->_required)
                            && trim($column) == ''
                        ) {
                            $this->addError(
                                str_replace(
                                    array('%field', '%row'),
                                    array($this->_fields[$col], $row),
                                    _T("Field %field is required, but missing in row %row")
                                )
                            );
                            return false;
                        }
                        $col++;
                    }
                }

                $row++;
            }
            fclose($handle);

            if ( !($row > 1) ) {
                //no data in file, just headers line
                $this->addError(
                    _T("File is empty!")
                );
                return false;
            } else {
                return true;
            }
        }

        return false;
    }

    /**
     * Store members in database
     *
     * @param string $filename CSV filename
     *
     * @return boolean
     */
    private function _storeMembers($filename)
    {
        $handle = fopen(self::DEFAULT_DIRECTORY . '/' . $filename, 'r');

        $row = 0;

        try {
            while ( ($data = fgetcsv(
                $handle,
                1000,
                self::DEFAULT_SEPARATOR,
                self::DEFAULT_QUOTE
            )) !== false) {
                if ( $row > 0 ) {
                    $col = 0;
                    $values = array();
                    foreach ( $data as $column ) {
                        $values[$this->_fields[$col]] = $column;
                        $col++;
                    }
                    //import member itself
                    $member = new Adherent();
                    //check for empty creation date
                    if ( isset($values['date_crea_adh']) && trim($values['date_crea_adh']) === '' ) {
                        unset($values['date_crea_adh']);
                    }
                    $valid = $member->check($values, $this->_required, null);
                    if ( $valid === true ) {
                        if ( $this->_dryrun === false ) {
                            $store = $member->store();
                            if ( $store !== true ) {
                                $this->addError(
                                    str_replace(
                                        array('%row', '%name'),
                                        array($row, $member->sname),
                                        _T("An error occured storing member at row %row (%name):")
                                    )
                                );
                                return false;
                            }
                        }
                    } else {
                        $this->addError(
                            str_replace(
                                array('%row', '%name'),
                                array($row, $member->sname),
                                _T("An error occured storing member at row %row (%name):")
                            )
                        );
                        if ( is_array($valid) ) {
                            foreach ( $valid as $e ) {
                                $this->addError($e);
                            }
                        }
                        return false;
                    }
                }
                $row++;
            }
            return true;
        } catch ( \Exception $e ) {
            $this->addError($e->getMessage());
        }

        return false;
    }

    /**
     * Return textual error message
     *
     * @param int $code The error code
     *
     * @return string Localized message
     */
    public function getErrorMessage($code)
    {
        $error = null;
        switch( $code ) {
        case self::DATA_IMPORT_ERROR:
            $error = _T("An error occured while importing members");
            break;
        }

        if ( $error === null ) {
            $error = $this->getErrorMessageFromCode($code);
        }

        return $error;
    }
}
