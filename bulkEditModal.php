<!-- Bulk Edit Modal -->
<div id="modalBulkEditor" data-width="560" class="modal-edit-member modal hide fade" tabindex="-1" role="dialog" aria-labelledby="bulkEditorEventTitle" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h4 id="bulkEditorEventTitle"></h4>
        <p class="text-info">В бланках регистрации будут изменены&nbsp;<b>только заполненные в этом окне</b>&nbsp;поля</p>
    </div>
    <div class="modal-body">
        <div class="controls">
            <div class="control-group row-fluid" style="width: 48%;">
                <label class="span12">Дата приезда <a href="#" class="beTooltipArrDate" rel="tooltip" data-placement="right" data-toggle="tooltip" title="??" tabindex="-1"><i class="icon-question-sign"></i></a></label>
                <input class="span12 beArrDate datepicker" type="text" maxlength="10" placeholder="ДД.ММ.ГГГГ" valid="date">
            </div>
            <div class="control-group row-fluid" style="width: 48%; float: right;">
                <label class="span12">Время приезда<a href="#" class="beTooltipArr" rel="tooltip" data-placement="right" data-toggle="tooltip" title="Время приезда к месту проведения конференции (с учётом времени на дорогу от вокзала/аэропорта)" tabindex="-1"><i class="icon-question-sign"></i></a></label>
                <input class="span12 beArrTime" type="text" maxlength="5" placeholder="ЧЧ:ММ" valid="time" tooltip="tooltipArr">
            </div>
            <div class="control-group row-fluid" style="width: 48%;">
                <label class="span12">Дата отъезда <a href="#" class="beTooltipDepDate" rel="tooltip" data-placement="right" data-toggle="tooltip" title="??" tabindex="-1"><i class="icon-question-sign"></i></a></label>
                <input class="span12 beDepDate datepicker" type="text" maxlength="10" placeholder="ДД.ММ.ГГГГ" valid="date">
            </div>
            <div class="control-group row-fluid" style="width: 48%; float: right;">
                <label class="span12">Время отъезда<a href="#" class="beTooltipDep" rel="tooltip" data-placement="right" data-toggle="tooltip" title="Время отъезда от места проведения конференции, а не от вокзала" tabindex="-1"><i class="icon-question-sign"></i></a></label>
                <input class="span12 beDepTime" type="text" maxlength="5" placeholder="ЧЧ:ММ" valid="time" tooltip="tooltipDep">
            </div>
            <div class="control-group row-fluid accom-block">
                <div class="control-group row-fluid " style="width: 48%;">
                    <label class="span12">Размещение</label>
                    <select class="span12 beAccom">
                        <option value='_none_' selected>&nbsp;</option>
                        <option value="1">ТРЕБУЕТСЯ</option>
                        <option value="0">НЕ ТРЕБУЕТСЯ</option>
                    </select>
                </div>

                <div class="control-group row-fluid" style="width: 48%; float: right;">
                    <label class="span12">Разместить с</label>
                    <select class="span12 beMate">
                        <option value='_none_' selected>&nbsp;</option>
                        <?php foreach (db_getServices() as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>"; ?>
                    </select>
                </div>
            </div>
            <div class="control-group row-fluid service-block">
                <div class="control-group row-fluid" style="width: 48%;">
                    <label class="span12">Служение</label>
                    <select class="span12 beService">
                        <option value='_none_' selected>&nbsp;</option>
                        <?php foreach (db_getServices() as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>"; ?>
                    </select>
                </div>
                <div class="control-group row-fluid" style="width: 48%; float: right;">
                    <label class="span12">Координатор<a href="#" rel="tooltip" data-placement="bottom" data-toggle="tooltip" title="Условия: возраст до 55 лет, здоровье и способность позаботиться о святых" tabindex="-1"><i class="icon-question-sign"></i></a></label>
                    <select class="span12 beCoord">
                        <option value='0'>---</option>
                        <option value='1'>РЕКОМЕНДУЕТСЯ</option>
                    </select>
                </div>
            </div>
            <div class="controls">
                <div class="control-group row-fluid">
                    <label class="span12">Статус<sup>*</sup></label>
                    <select class="span12 beStatus" valid="required">
                        <option value='_none_' selected>&nbsp;</option>
                        <?php foreach (db_getStatuses() as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>"; ?>
                    </select>
                </div>
            </div>
            <div class="control-group row-fluid block-transport">
                <label class="span12 beLblTransport"><span class="transportText"></span><a href="#" class="transportHint" rel="tooltip" data-toggle="tooltip" title="" tabindex="-1"><i class="icon-question-sign"></i></a></label>
                <div class="control-group row-fluid beGrpTransport">
                    <select class="span12 beTransport">
                        <option value='_none_' selected>&nbsp;</option>
                        <option value="1">ТРЕБУЕТСЯ</option>
                        <option value="0">НЕ ТРЕБУЕТСЯ</option>
                    </select>
                </div>
            </div>
            <div class="show-admin-fields">
                <div class="control-group row-fluid">
                    <label class="span12">Прибыл</label>
                    <select class="span12 emAttended">
                        <option value='_none_'>&nbsp;</option>
                        <option value='0'>НЕТ</option>
                        <option value='1'>ДА</option>
                    </select>
                </div>
                <div class="control-group row-fluid">
                    <label class="span12">Расселение</label>
                    <input class="span12 emPlace" type="text">
                </div>
                <div class="control-group row-fluid">
                    <label class="span12">Взнос</label>
                    <input class="span12 emPaid" type="text">
                </div>
                <div class="control-group row-fluid prepaidBlock">
                    <label class="span12">Предварительная оплата</label>
                    <input class="span12 emPrepaid" disabled type="text">
                </div>
                <div class="control-group row-fluid prepaidBlock">
                    <label class="span12">Финансовая помощь</label>
                    <input class="span12 emAidpaid" disabled type="text">
                </div>
            </div>
        </div>
        <div style="margin-left:30px">
            <hr/>
            <p class="text-info" style="color: rgba(255, 0, 0, 0)">Будут изменены только заполненные поля</p>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-info disable-on-invalid" id="btnDoSaveBulk">Сохранить</button>
        <button class="btn" data-dismiss="modal" aria-hidden="true">Отменить</button>
    </div>
</div>
