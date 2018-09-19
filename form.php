<?php
    if (!isset ($isGuest)) $isGuest = false;
    if (!isset ($noEvent)) $noEvent = false;
    if (!isset ($indexPage)) $indexPage = false;
    function g ($s1, $s2='') {global $isGuest; echo $isGuest ? $s1 : $s2;}
    function e ($s1, $s2='') {global $noEvent; echo $noEvent ? $s2 : $s1;}
    function ge ($s1, $s2='') {global $isGuest, $noEvent; echo $isGuest && !$noEvent ? $s1 : $s2;}
?>

<div class="desctop-visible">
<div class="controls">
    <label class="span4">ФИО<sup>*</sup> <!--<a href="#" id="tooltipName" rel="tooltip" data-placement="right" data-toggle="tooltip" title="Если фамилия была изменена недавно, пожалуйста, укажите в скобках прежнюю фамилию" tabindex="-1"><i class="icon-question-sign" id="tooltipNameHelp"></i></a>--><span class="example">Пример: Орлов Пётр Иванович</span></label>
    <label class="span2">Дата рождения<?php //ge('<sup>*</sup>'); ?><sup>*</sup></label>
    <label class="span1">Пол<sup>*</sup></label>
    <label class="span2">Русскоязычный</label>
</div>
<div class="controls controls-row">
    <div class="control-group">
        <input class="span4 emName" style="margin-bottom:0;" tooltip="tooltipName" type="text" maxlength="70" valid="required" placeholder="Фамилия Имя Отчество">
        <i class="icon-pencil unblock-input" style="display: none"></i>
        <span class="name-hint" style="font-size:10px; color:#da7575; display: none; margin-left: 30px;">Если фамилия была изменена недавно, укажите в скобках прежнюю фамилию</span>
    </div>
    <div class="control-group">
        <input class="span2 emBirthdate datepicker" type="text"  maxlength="10" placeholder="ДД.ММ.ГГГГ" valid="required,<?php //ge ('required, '); ?>date">
    </div>
    <div class="control-group">
        <select class="span1 emGender" valid="required">
            <option value='_none_' selected>&nbsp;</option>
            <option value='male'>MУЖ</option>
            <option value='female'>ЖЕН</option>
        </select>
    </div>
    <div class="control-group">
        <select class="span2 emRussianLanguage">
            <option value='0' >НЕТ</option>
            <option value='1' selected>ДА</option>
        </select>
    </div>
</div>

<div class="controls">
    <label class="span2">Гражданство<sup>*</sup></label>
    <?php if ($isGuest || $indexPage) { ?>
        <label class="span7">Населенный пункт<sup>*</sup><span class="example">Введите название. Если ваш населённый пункт появится в списке, выберите его</span></label>
    <?php } else { ?>
        <label class="span2">Населенный пункт<sup>*</sup></label>
        <label class="span5">Если населенного пункта в списке нет, то укажите его здесь:</label>
    <?php } ?>
</div>
<div class="controls controls-row">
        <div class="control-group">
            <select class="span2 emCitizenship" valid="required">
                <option value='_none_' selected>&nbsp;</option>
                <?php foreach ($countries1 as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>"; ?>
                <option disabled="disabled">---------------------------</option>
                <?php foreach ($countries2 as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>"; ?>
            </select>
        </div>
    <?php if ($isGuest || $indexPage) { ?>
        <div class="control-group localityControlGroup">
            <input class="span7 locality-autocomplete emNewLocality" type="text" maxlength="50" valid="required">
            <i class="icon-pencil unblock-input" style="display: none"></i>
        </div>
    <?php } else { ?>
        <div class="control-group localityControlGroup">
            <select class="span2 emLocality" valid="required">
                <!-- <option value='_none_' selected>&nbsp;</option> -->
                <?php
                //$page = ($_SERVER['PHP_SELF']);
                /*
                    if($page == '/reg.php' || $page == '/members.php'){
                        foreach ($localities as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>";
                    }
                    else{
                        foreach ($loc as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>";
                    }
                    */
                ?>
            </select>
            <input class="span5 locality-autocomplete emNewLocality" type="text" maxlength="50" valid="required">
            <i class="icon-pencil unblock-input" style="display: none"></i>
        </div>
    <?php } ?>
</div>
<div class="controls">
    <label class="span6">Почтовый адрес<?php e('<sup>*</sup>'); ?><span class="example">Пример: Россия, 180000, Псковская обл., г. Псков, ул. Труда 5, кв. 6</span></label>
    <label class="span3">Email<?php /* e */ g('<sup id="supEmailRequred">*</sup>'); ?></label>
</div>
<div class="controls controls-row">
    <div class="control-group"><input class="span6 emAddress" type="text" maxlength="150" <?php e('valid="required"'); ?>></div>
    <div class="control-group"><input class="span3 emEmail" type="email" maxlength="50" valid="<?php /* e */ g('required, '); ?>email"></div>
</div>
<div class="controls">
    <label class="span<?php e(2,2);?>">Домашний тел.</label>
    <label class="span<?php e(2,2);?>">Мобильный тел. <a href="#" id="tooltipCellphone" rel="tooltip" data-placement="right" data-toggle="tooltip" title="Если имеется несколько номеров, укажите их через запятую" tabindex="-1"><i class="icon-question-sign"></i></a></label>
    <?php if ($noEvent) {?>
    <label class="span2">Дата крещения</label>
    <?php } ?>
    <?php if (!$noEvent) {?>
        <label class="span2">Мобильный врем. <a href="#" id="tooltipTempPhone" rel="tooltip" data-placement="right" data-toggle="tooltip" title="Номер телефона на время проведения мероприятия. Можно не заполнять, если используется основной мобильный номер" tabindex="-1"><i class="icon-question-sign"></i></a></label>
    <?php } ?>
    <?php if (!$isGuest && !$indexPage) { ?>
    <label class="span3"><?php g ('&nbsp;','Категория'.($noEvent ? '' : '<sup>*</sup>')); ?></label>
    <?php } else { ?>
    <label class="span3">&nbsp;</label>
    <?php } ?>
</div>
<div class="controls controls-row">
    <input class="span<?php e(2,2);?> emHomePhone" type="text" maxlength="50" placeholder="+XXXXXXXXXX">
    <input class="span<?php e(2,2);?> emCellPhone" type="text" maxlength="50" placeholder="+XXXXXXXXXX" tooltip="tooltipCellphone">
    <?php if ($noEvent) {?>
    <input class="span2 emBaptized datepicker" type="text" maxlength="10" placeholder="ДД.ММ.ГГГГ" valid="date">
    <?php } ?>
    <?php if (!$noEvent) {?>
        <input class="span2 emTempPhone" type="text" maxlength="50" placeholder="+XXXXXXXXXX" tooltip="tooltipTempPhone">
    <?php } ?>
    <?php if (!$isGuest && !$indexPage) { ?>
    <div class="control-group">
        <select class="span3 emCategory" valid="required" <?php //e('valid="required"');?>>
            <option value='_none_' selected>&nbsp;</option>
            <?php foreach (db_getCategories() as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>"; ?>
        </select>
    </div>
    <?php } ?>
    <?php if (!$isGuest && !$indexPage) { ?>
        <!--
            If a form is open by a guest (on main page) Status field is placed after Phones fields.
            But if a form open by admin a Status field is placed after Admins comment
        -->
        <!--
        <div class="control-group">
            <select class="span3 emStatus" valid="required">
                <option value='_none_' selected>&nbsp;</option>
                <?php //foreach (db_getStatuses() as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>"; ?>
            </select>
        </div> -->
    <?php } ?>
</div>
<div class="controls passport-info" style="display: none">
    <label class="span2">Тип документа<?php e('<sup>*</sup>'); ?></label>
    <label class="span2">Номер документа<?php e('<sup>*</sup>'); ?></label>
    <label class="span2">Дата выдачи<?php e('<sup>*</sup>'); ?></label>
    <label class="span3">Кем выдан<?php e('<sup>*</sup>'); ?></label>
</div>
<div class="controls controls-row passport-info" style="display: none">
    <div class="control-group passport-info">
        <select class="span2 emDocumentType" <?php e('valid="required"');?>>
            <option value='_none_' selected>&nbsp;</option>
            <?php foreach (db_getDocuments() as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>"; ?>
        </select>
    </div>
    <div class="control-group passport-info"><input class="span2 emDocumentNum" type="text" maxlength="20" <?php e('valid="required"');?>></div>
    <div class="control-group passport-info"><input class="span2 emDocumentDate datepicker" type="text" maxlength="10" valid="<?php e('required, ');?>date" placeholder="ДД.ММ.ГГГГ"></div>
    <div class="control-group passport-info"><input class="span3 emDocumentAuth" type="text" maxlength="150" <?php e('valid="required"');?>></div>
</div>
<div class="controls tp-passport-info">
    <label class="span2 tp-passport-info">Номер загранпаспорта<?php e('<sup>*</sup>'); ?></label>
    <label class="span2 tp-passport-info">Страна выдачи<?php e('<sup>*</sup>'); ?> <a href="#" rel="tooltip" data-placement="right" data-toggle="tooltip" title="Укажите название страны по-английски" tabindex="-1"><i class="icon-question-sign"></i></a></label>
    <label class="span2 tp-passport-info">Окончание действия<?php e('<sup>*</sup>'); ?> <a href="#" rel="tooltip" data-placement="right" data-toggle="tooltip" title="Дата окончания действия загранпаспорта" tabindex="-1"><i class="icon-question-sign"></i></a></label>
    <label class="span3 tp-passport-info">Фамилия и имя латинскими буквами<?php e('<sup>*</sup>'); ?></label>
</div>
<div class="controls controls-row tp-passport-info">
    <div class="control-group tp-passport-info"><input class="span2 emDocumentNumTp" type="text" maxlength="20" <?php e('valid="required"');?>></div>
    <div class="control-group tp-passport-info"><input class="span2 emDocumentAuthTp" type="text" maxlength="20" <?php e('valid="required"');?>></div>
    <div class="control-group tp-passport-info"><input class="span2 emDocumentDateTp datepicker" type="text" maxlength="10" valid="<?php e('required, ');?>date" placeholder="ДД.ММ.ГГГГ"></div>
    <div class="control-group tp-passport-info"><input class="span3 emDocumentNameTp" type="text" maxlength="150" <?php e('valid="required"');?>></div>
</div>
<?php if (!$noEvent) { ?>
<div class="controls">
    <label class="span1">Приезд<sup>*</sup> <a href="#" class="tooltipArrDate" rel="tooltip" data-placement="right" data-toggle="tooltip" title="" tabindex="-1"><i class="icon-question-sign"></i></a></label>
    <label class="span1">Время <a href="#" id="tooltipArr" rel="tooltip" data-placement="right" data-toggle="tooltip" title="Время приезда к месту проведения конференции (с учётом времени на дорогу от вокзала/аэропорта)" tabindex="-1"><i class="icon-question-sign"></i></a></label>
    <label class="span1">Отъезд<sup>*</sup> <a href="#" class="tooltipDepDate" rel="tooltip" data-placement="right" data-toggle="tooltip" title="" tabindex="-1"><i class="icon-question-sign"></i></a></label>
    <label class="span1">Время <a href="#" id="tooltipDep" rel="tooltip" data-placement="right" data-toggle="tooltip" title="Время отъезда от места проведения конференции, а не от вокзала" tabindex="-1"><i class="icon-question-sign"></i></a></label>
    <label class="span2">Размещение<sup>*</sup></label>
    <label class="span3 parking">Парковка<sup>*</sup> <a href="#" id="tooltipParking" rel="tooltip" data-placement="right" data-toggle="tooltip" title="Только для владельцев автомобилей" tabindex="-1"><i class="icon-question-sign"></i></a></label>
    <label class="span3 flight-info">Гостиница и кол-во мест <a href="#" id="tooltipNote" rel="tooltip" data-placement="right" data-toggle="tooltip" title="Укажите название гостиницы и количество мест в номере.  Пример: 'Sachsen Park Hotel (2-мест.)'" tabindex="-1"><i class="icon-question-sign"></i></a></label>
</div>
<div class="controls controls-row">
    <div class="control-group"><input class="span1 emArrDate datepicker-form" readonly type="text" maxlength="5" placeholder="ДД.ММ" valid="required,ddmm"></div>
    <div class="control-group"><input class="span1 emArrTime" type="text" maxlength="5" placeholder="ЧЧ:ММ" valid="time" tooltip="tooltipArr"></div>
    <div class="control-group"><input class="span1 emDepDate datepicker-form" readonly type="text" maxlength="5" placeholder="ДД.ММ" valid="required,ddmm"></div>
    <div class="control-group"><input class="span1 emDepTime" type="text" maxlength="5" placeholder="ЧЧ:ММ" valid="time" tooltip="tooltipDep"></div>
    <div class="control-group">
        <select class="span2 emAccom" valid="required" tooltip="tooltipParking">
            <option value='_none_' selected>&nbsp;</option>
            <option value="1">ТРЕБУЕТСЯ</option>
            <option value="0">НЕ ТРЕБУЕТСЯ</option>
        </select>
    </div>
    <div class="control-group parking">
        <select class="span3 emParking" valid="required">
            <option value='_none_' selected>&nbsp;</option>
            <option value="1">НУЖНА</option>
            <option value="0">НЕ НУЖНА</option>
        </select>
    </div>
    <div class="control-group"><input class="span3 emFlightNote flight-info" type="text" maxlength="100"></div>
</div>
<div class="controls flight-info">
    <label class="span2">Авиарейс (прибытие) <a href="#" id="tooltipArr" rel="tooltip" data-placement="right" data-toggle="tooltip" title="Авиакомпания, рейс и аэропорт прибытия" tabindex="-1"><i class="icon-question-sign"></i></a></label>
    <label class="span2">Авиарейс (вылет) <a href="#" id="tooltipArr" rel="tooltip" data-placement="right" data-toggle="tooltip" title="Авиакомпания, рейс и аэропорт вылета" tabindex="-1"><i class="icon-question-sign"></i></a></label>
    <label class="span2">Английский</label>
    <label class="span3">Виза</label>
</div>
<div class="controls controls-row flight-info">
    <div class="control-group"><input class="span2 emFlightNumArr" type="text" maxlength="30"></div>
    <div class="control-group"><input class="span2 emFlightNumDep" type="text" maxlength="30"></div>
    <div class="control-group ">
        <select class="span2 emEnglishLevel" valid="required">
            <option value="_none_" selected>&nbsp;</option>
            <option value="0">Не владеет</option>
            <option value="1">Начальный уровень</option>
            <option value="2">Хороший уровень</option>
        </select>
    </div>
    <div class="control-group ">
        <select class="span3 emVisa" valid="required">
            <option value="_none_" selected>&nbsp;</option>
            <option value="1">Не требуется</option>
            <option value="2">Уже есть или получу для другой поездки</option>
            <option value="3">Получу для этой поездки</option>
        </select>
    </div>
</div>
<?php if (!$isGuest && !$indexPage) { ?>
<div class="controls">
    <label class="span1">Взнос</label>
    <label class="span1 label-prepaid">Внесено</label>
    <label class="span1 label-currency">Валюта</label>
    <?php if (!$isGuest && !$noEvent && !$indexPage) { ?>
        <label class="span1">Коорд. <a href="#" rel="tooltip" data-placement="bottom" data-toggle="tooltip" title="Условия: возраст до 55 лет, здоровье и способность позаботиться о святых" tabindex="-1"><i class="icon-question-sign"></i></a></label>
    <?php } ?>
    <label class="span2">Разместить с</label>
    <label class="span3">Служение</label>
</div>
<div class="controls controls-row">
    <div class="control-group"><input class="span1 emContrib" type="text" maxlength="4" disabled></div>
    <div class="control-group"><input class="span1 emPrepaid" type="text" maxlength="4" ></div>
    <div class="control-group">
        <select class="span1 emCurrency" disabled>
            <option value='_none_' selected>&nbsp;</option>
            <?php
                 foreach (db_getCurrencies() as $key => $val) {
                     echo "<option value='$key'>". htmlspecialchars($val)."</option>";
                 }
            ?>
        </select>
    </div>
    <?php if (!$isGuest && !$noEvent && !$indexPage) { ?>
    <div class="control-group">
        <select class="span1 emCoord">
            <option value="0">---</option>
            <option value="1">РЕКОМЕНДУЕТСЯ</option>
        </select>
    </div>
    <?php } ?>
    <select class="span2 emMate"></select>
    <select class="span3 emService">
        <option value='_none_' selected>&nbsp;</option>
        <?php foreach (db_getServices() as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>"; ?>
    </select>
</div>
<?php } } ?>
<?php if($noEvent && !$isGuest && !$indexPage){ ?>
<div class="controls school-fields">
    <label class="span4">Школа <span class="emClassLevel"></span></label>
    <label class="span1">Начало <a href="#" rel="tooltip" data-placement="right" data-toggle="tooltip" title="Год начала учебы в школе" tabindex="-1"><i class="icon-question-sign"></i></a></label>
    <label class="span1">Конец <a href="#" rel="tooltip" data-placement="right" data-toggle="tooltip" title="Год окончания учебы в школе" tabindex="-1"><i class="icon-question-sign"></i></a></label>
    <label class="span3">Примечание</label>
</div>
<div class="controls controls-row school-fields">
    <div class="control-group"><input type="text" class="span4" disabled value="Средняя школа"></div>
    <div class="control-group"><input class="span1 emSchoolStart" placeholder="ГГГГ" type="text" maxlength="4" ></div>
    <div class="control-group"><input class="span1 emSchoolEnd" placeholder="ГГГГ" type="text" maxlength="4" ></div>
    <div class="control-group"><input class="span3 emSchoolComment" type="text" maxlength="100" ></div>
</div>
<div class="controls college-fields">
    <label class="span4">Учебное заведение <span class="emCourseLevel"></span></label>
    <label class="span1">Начало <a href="#" rel="tooltip" data-placement="right" data-toggle="tooltip" title="Год начала учебы в вузе" tabindex="-1"><i class="icon-question-sign"></i></a></label>
    <label class="span1">Конец <a href="#" rel="tooltip" data-placement="right" data-toggle="tooltip" title="Год окончания учебы в вузе" tabindex="-1"><i class="icon-question-sign"></i></a></label>
    <label class="span3">Примечание</label>
</div>
<div class="controls controls-row college-fields">
    <div class="control-group">
        <select class="span4 emCollege">
            <option value='_none_' selected>&nbsp;</option>
            <?php foreach ($colleges as $id => $name) echo "<option value='$id'>". htmlspecialchars($name)."</option>"; ?>
        </select>
    </div>
    <div class="control-group"><input class="span1 emCollegeStart" type="text" placeholder="ГГГГ" maxlength="4" ></div>
    <div class="control-group"><input class="span1 emCollegeEnd" type="text" placeholder="ГГГГ" maxlength="4" ></div>
    <div class="control-group"><input class="span3 emCollegeComment" type="text" maxlength="100" ></div>
</div>
<?php }
if ($noEvent) { ?>
    <div class="controls">
        <label class="span6">Комментарий администратора <a href="#" rel="tooltip" data-toggle="tooltip" title="виден только администраторам" tabindex="-1"><i class="icon-question-sign"></i></a></label>
        <label class="span3">Уровень английского</label>
    </div>
    <div class="controls controls-row">
        <input class="span6 emComment" type="text">
        <select class="span3 emEnglishLevel" >
            <option value="0" selected>Не владеет</option>
            <option value="1">Начальный уровень</option>
            <option value="2">Хороший уровень</option>
        </select>
    </div>
<?php } else  {
    if(!$isGuest && !$indexPage){
?>
<div class="controls needAid">
    <label class="span2 emAidLabel">Финансовая помощь</label>
    <label class="span2 emContrAmountLabel">Не хватает на взнос <a href="#" id="tooltipCountAmount" rel="tooltip" data-placement="right" data-toggle="tooltip" title="Введите сумму в гривнах" tabindex="-1"><i class="icon-question-sign"></i></a></label>
    <label class="span2 emTransAmountLabel">Не хватает на дорогу <a href="#" id="tooltipTransAmount" rel="tooltip" data-placement="right" data-toggle="tooltip" title="Введите сумму в гривнах" tabindex="-1"><i class="icon-question-sign"></i></a></label>
    <label class="span3 emFellowshipLabel">Общение об этом с братьями<sup>*</sup> <a href="#" rel="tooltip" data-placement="bottom" data-toggle="tooltip" tabindex="-1" title="Перед обращением за финансовой помощью вам нужно пообщаться об этом с братьями в вашей местности"><i class="icon-question-sign"></i></a></label>
</div>
<div class="controls controls-row needAid">
    <div class="control-group">
        <select class="emAid span2">
            <option value="0" selected>Не нужна</option>
            <option value="1">Нужна</option>
        </select>
    </div>
    <div class="control-group">
        <input type="text" class="emContrAmount span2">
    </div>
    <div class="control-group">
        <input type="text" class="emTransAmount span2">
    </div>
    <div class="control-group">
        <select class="emFellowship span3" valid="required">
            <option value="_none_" selected="">&nbsp;</option>
            <option value="0">Не было</option>
            <option value="1">Было</option>
        </select>
    </div>
</div>
        <?php } ?>
<!--
    If a form is open by a guest (on main page) Status field is placed after Phones fields.
    But if a form open by admin a Status field is placed after Admins comment
-->
<?php if (!$isGuest && !$indexPage) { ?>
<div class="controls">
    <label class="span6">Комментарий администратора</label>
    <label class="span3">Статус<sup>*</sup></label>
</div>
<div class="controls controls-row">
    <input class="span6 emComment" type="text">
    <div class="control-group">
        <select class="span3 emStatus" valid="required">
            <option value='_none_' selected>&nbsp;</option>
            <?php foreach (db_getStatuses() as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>"; ?>
        </select>
    </div>
</div>
<?php } ?>
<div class="controls">
    <label class="span6">Комментарий участника</label>
    <label class="span3 grpTransport" id="lblTransport"><span class="transportText"></span><sup>*</sup> <a href="#" class="transportHint" rel="tooltip" data-toggle="tooltip" title="" tabindex="-1"><i class="icon-question-sign"></i></a></label>
</div>
<div class="controls controls-row">
    <input class="span6 emUserComment" type="text">
    <div class="control-group grpTransport">
        <select class="span3 emTransport" valid="required">
            <option value='_none_' selected>&nbsp;</option>
            <option value="1">ТРЕБУЕТСЯ</option>
            <option value="0">НЕ ТРЕБУЕТСЯ</option>
        </select>
    </div>
</div>
<?php if($indexPage){?>
<div class="controls">
    <input id="terms-use-checkbox" type="checkbox" style="float: left; margin-top: 3px; margin-left: 30px;">
    <label for="terms-use-checkbox" class="span6" style="margin-left: 5px;">подтверждаю согласие на обработку моих персональных данных</label>    
</div>
<?php } ?>
<?php } ?>
</div>
