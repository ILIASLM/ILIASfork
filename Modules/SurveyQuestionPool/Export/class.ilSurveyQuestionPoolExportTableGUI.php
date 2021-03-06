<?php

/* Copyright (c) 1998-2019 ILIAS open source, Extended GPL, see docs/LICENSE */

/**
 *
 * @author Helmut Schottmüller <ilias@aurealis.de>
 */
class ilSurveyQuestionPoolExportTableGUI extends ilTable2GUI
{
    protected $confirmdelete;
    protected $counter;
    
    /**
     * Constructor
     *
     * @access public
     * @param
     * @return
     */
    public function __construct($a_parent_obj, $a_parent_cmd, $confirmdelete = false)
    {
        global $DIC;

        parent::__construct($a_parent_obj, $a_parent_cmd);

        $lng = $DIC->language();
        $ilCtrl = $DIC->ctrl();

        $this->lng = $lng;
        $this->ctrl = $ilCtrl;
        $this->confirmdelete = $confirmdelete;
        $this->counter = 0;
        
        $this->setFormName('phrases');
        $this->setTitle($this->lng->txt('svy_export_files'));
        $this->setStyle('table', 'fullwidth');
        if (!$confirmdelete) {
            $this->addColumn('', 'f', '1%');
        }
        $this->addColumn($this->lng->txt("file"), 'file', '');
        $this->addColumn($this->lng->txt("size"), 'size', '');
        $this->addColumn($this->lng->txt("date"), 'date', '');

        if ($confirmdelete) {
            $this->addCommandButton('deleteExportFile', $this->lng->txt('confirm'));
            $this->addCommandButton('cancelDeleteExportFile', $this->lng->txt('cancel'));
        } else {
            $this->addMultiCommand('downloadExportFile', $this->lng->txt('download'));
            $this->addMultiCommand('confirmDeleteExportFile', $this->lng->txt('delete'));
        }

        $this->setRowTemplate("tpl.il_svy_qpl_export_row.html", "Modules/SurveyQuestionPool");

        $this->setFormAction($this->ctrl->getFormAction($a_parent_obj, $a_parent_cmd));
        $this->setDefaultOrderField("file");
        $this->setDefaultOrderDirection("asc");
        
        if ($confirmdelete) {
            $this->disable('sort');
            $this->disable('select_all');
        } else {
            $this->setPrefix('file');
            $this->setSelectAllCheckbox('file');
            $this->enable('sort');
            $this->enable('select_all');
        }
        $this->enable('header');
    }

    /**
     * fill row
     *
     * @access public
     * @param
     * @return
     */
    public function fillRow($data)
    {
        if (!$this->confirmdelete) {
            $this->tpl->setCurrentBlock('checkbox');
            $this->tpl->setVariable('CB_ID', $this->counter);
            $this->tpl->setVariable('CB_FILENAME', ilUtil::prepareFormOutput($data['file']));
            $this->tpl->parseCurrentBlock();
        } else {
            $this->tpl->setCurrentBlock('hidden');
            $this->tpl->setVariable('HIDDEN_FILENAME', ilUtil::prepareFormOutput($data['file']));
            $this->tpl->parseCurrentBlock();
        }
        $this->tpl->setVariable('CB_ID', $this->counter);
        $this->tpl->setVariable("PHRASE", $data["phrase"]);
        $this->tpl->setVariable("FILENAME", ilUtil::prepareFormOutput($data['file']));
        $this->tpl->setVariable("SIZE", $data["size"]);
        $this->tpl->setVariable("DATE", $data["date"]);
        $this->counter++;
    }
}
