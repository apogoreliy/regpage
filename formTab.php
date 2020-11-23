<?php
    if (!isset ($isGuest)) $isGuest = false;
    if (!isset ($noEvent)) $noEvent = false;
    if (!isset ($indexPage)) $indexPage = false;
    function g ($s1, $s2='') {global $isGuest; echo $isGuest ? $s1 : $s2;}
    function e ($s1, $s2='') {global $noEvent; echo $noEvent ? $s2 : $s1;}
    function ge ($s1, $s2='') {global $isGuest, $noEvent; echo $isGuest && !$noEvent ? $s1 : $s2;}
?>
<!--  Portrait tablet to landscape and desktop -->
<!--<div class="tablets-visible"> -->
<div class="edit-member-form">
<div class="controls" style="height: 180px;">
    <div class="control-group row-fluid">
        <label class="span12">ФИО<sup>*</sup>
        <span class="example">Пример: Орлов Пётр Иванович</span></label>
        <input class="span12 emName" tooltip="tooltipName" type="text" maxlength="70" valid="required" placeholder="Фамилия Имя Отчество">
        <i class="icon-pencil unblock-input" style="display: none;"></i>
    </div>
    <div class="control-group row-fluid" style="width: 48%">
        <label id="emBirthdateLabelSup" class="span12">Дата рождения</label>
        <input class="span12 emBirthdate" type="date"  maxlength="10" valid="">
    </div>
    <div class="control-group row-fluid" style="width: 48%; float: right;">
        <label class="span12">Пол<sup>*</sup></label>
        <select class="span12 emGender" valid="required">
            <option value='_none_' selected>&nbsp;</option>
            <option value='male'>MУЖ</option>
            <option value='female'>ЖЕН</option>
        </select>
    </div>
    <div class="control-group row-fluid" style="width: 48%;">
        <label class="span12">Гражданство<sup>*</sup></label>
        <select class="span12 emCitizenship" valid="required">
            <option value='_none_' selected>&nbsp;</option>
            <?php foreach ($countries1 as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>"; ?>
            <option disabled="disabled">---------------------------</option>
            <?php foreach ($countries2 as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>"; ?>
        </select>
    </div>
    <div class="control-group row-fluid" style="width: 48%; float: right;">
        <label class="span12">Русскоязычный</label>
        <select class="span12 emRussianLanguage">
            <option value='0' >НЕТ</option>
            <option value='1' selected>ДА</option>
        </select>
    </div>
</div>
<div class="controls">
    <?php if ($isGuest || $indexPage) { ?>
    <div style="margin-bottom: 10px" class="control-group row-fluid">
        <label class="span12">Населенный пункт<sup>*</sup></label>
        <div class="localityControlGroup">
            <input style="margin-bottom: 0" class="span12 locality-autocomplete emNewLocality" type="text" maxlength="50" valid="required">
            <i class="icon-pencil unblock-input" style="display: none; margin-top: -22px; margin-left: 0; margin-right: 10px; float: right;"></i>
            <span style="margin-left: 0" class="example">Введите название. Если нужный населённый пункт появится в списке, выберите его</span>
        </div>
    </div>
    <?php } else { ?>
    <div class="control-group row-fluid">
        <label class="span12">Населенный пункт<sup>*</sup></label>
        <div class="localityControlGroup">
          <input type="text" id="inputEmLocalityId" class="span12 inputEmLocalityClass" name="" value="" valid="required" data-value_input="" data-text_input="">
          <!--  <input type="text" id="inputEmLocalityId2" class="span12" data-value_input="" data-text_input="">-->
          <!--<datalist id="inputEmLocalityData" class="inputEmLocality">
          </datalist>-->
          <div class="modalListInput inputEmLocality" style="">
          </div>
            <select class="span12 emLocality" valid="required" data-value="" data-text="">
            </select>
        </div>
    </div>
    <div style="margin-bottom: 10px; color: cadetblue; float:left;">
        <span class="handle-new-locality">Нужного населённого пункта нет в списке?</span>
    </div>
    <div class="control-group row-fluid block-new-locality">
        <input class="span12 locality-autocomplete emNewLocality" placeholder="Введите название населённого пункта в этом поле" value="<?php echo $member['new_locality']; ?>" type="text" maxlength="50">
        <i class="icon-pencil unblock-input" style="display: none"></i>
    </div>
    <?php } ?>

    <div class="control-group row-fluid address_block">
        <label class="span12">Почтовый адрес<?php e('<sup>*</sup>'); ?><span class="example">Пример: Россия, 180000, Псковская обл., г. Псков, ул. Труда 5, кв. 6</span></label>
        <input class="span12 emAddress" type="text" maxlength="150" <?php e('valid="required"'); ?>>
    </div>
    <div class="control-group row-fluid">
        <label class="span12">Email<?php g('<sup id="supEmailRequred">*</sup>'); ?></label>
        <input class="span12 emEmail" type="email" maxlength="50" valid="<?php g('required, '); ?>email">
    </div>
    <div class="control-group row-fluid">
        <label class="span12">Мобильный телефон <span class="example">Если имеется несколько номеров, укажите их через запятую</span></label>
        <input class="span12 emCellPhone" type="text" maxlength="50" placeholder="+XXXXXXXXXX" tooltip="tooltipCellphone">
    </div>

    <?php //if (!$noEvent) {?>
    <!--<div class="control-group row-fluid">
        <label class="span12">Мобильный врем. <a href="#" id="tooltipTempPhone" rel="tooltip" data-placement="right" data-toggle="tooltip" title="Номер телефона на время проведения мероприятия. Можно не заполнять, если используется основной мобильный номер" tabindex="-1"><i class="icon-question-sign"></i></a></label>
        <input class="span12 emTempPhone" type="text" maxlength="50" placeholder="+XXXXXXXXXX" tooltip="tooltipTempPhone">
    </div>-->
    <?php //} ?>

    <div class="control-group row-fluid"
    <?php if (!$isGuest && !$indexPage) { ?>
      style="display:inline-block"
    <?php } else {?>
      style="display: none"
    <?php } ?>
    >
        <label class="span12"><?php g ('&nbsp;','Категория'.($noEvent ? '' : '<sup>*</sup>')); ?></label>
        <select class="span12 emCategory" valid="required" <?php //e('valid="required"');?>>
            <option value='_none_' selected>&nbsp;</option>
            <?php foreach (db_getCategories() as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>"; ?>
        </select>
    </div>

    <?php if ($noEvent) {?>
    <div class="control-group row-fluid">
        <label class="span12">Дата крещения</label>
        <input class="span12 emBaptized datepicker" type="text" maxlength="10" placeholder="ДД.ММ.ГГГГ" valid="date">
    </div>
    <div class="control-group row-fluid handle-passport-info" style="margin-bottom: 10px;color: cadetblue">
        <strong>Паспортные данные</strong>
        <i style="margin-left: 10px;" class="fa fa-chevron-down fa-lg"></i>
    </div>
    <?php } ?>
</div>
<?php if($noEvent) { echo '<div class="block-passport-info" style="display: none;">'; } ?>
    <div class="controls passport-info">
        <div class="control-group row-fluid passport-info">
            <label class="span12">Тип документа<?php e('<sup>*</sup>'); ?></label>
            <select class="span12 emDocumentType" <?php e('valid="required"');?>>
                <option value='_none_' selected>&nbsp;</option>
                <?php foreach (db_getDocuments() as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>"; ?>
            </select>
        </div>
        <div class="control-group row-fluid passport-info" style="width: 48%;">
            <label class="span12">Номер документа<?php e('<sup>*</sup>'); ?></label>
            <input class="span12 emDocumentNum" type="text" maxlength="20" <?php e('valid="required"');?>>
        </div>
        <div class="control-group row-fluid passport-info" style="width: 48%; float: right;">
            <label class="span12">Дата выдачи<?php e('<sup>*</sup>'); ?></label>
            <input class="span12 emDocumentDate datepicker" type="text" maxlength="10" valid="<?php e('required, ');?>date" placeholder="ДД.ММ.ГГГГ">
        </div>
        <div class="control-group row-fluid passport-info">
            <label class="span12">Кем выдан<?php e('<sup>*</sup>'); ?></label>
            <input class="span12 emDocumentAuth" type="text" maxlength="150" <?php e('valid="required"');?>>
        </div>
    </div>
    <div class="controls tp-passport-info">
        <div class="control-group row-fluid">
            <label class="span12">Номер загранпаспорта<?php e('<sup>*</sup>'); ?></label>
                <input class="span12 emDocumentNumTp" type="text" maxlength="20" <?php e('valid="required"');?>>
        </div>
        <div class="control-group row-fluid">
            <label class="span12">Страна, которой выдан паспорт. Укажите название страны по-английски<?php e('<sup>*</sup>'); ?></label>
                <input class="span12 emDocumentAuthTp" type="text" maxlength="20" valid="<?php e('required, ');?>">
        </div>
        <div class="control-group row-fluid">
            <label class="span12">Дата окончания действия загранпаспорта<?php e('<sup>*</sup>'); ?></label>
                <input class="span12 emDocumentDateTp datepicker" type="text" maxlength="10" valid="<?php e('required, ');?>date" placeholder="ДД.ММ.ГГГГ">
        </div>
        <div class="control-group row-fluid">
            <label class="span12">Фамилия и имя латинскими буквами (как указано в загранпаспорте)<?php e('<sup>*</sup>'); ?></label>
                <input class="span12 emDocumentNameTp" type="text" maxlength="150" <?php e('valid="required"');?>>
        </div>
    </div>
<?php if($noEvent) { echo '</div>'; } ?>

<?php if(!$noEvent){ ?>
<div class="controls tp-passport-info" style="display: none">
    <div class="control-group row-fluid">
        <label class="span12">Уровень английского</label>
        <select class="span12 emEnglishLevel" >
            <option value="0" selected>Не владеет</option>
            <option value="1">Начальный уровень</option>
            <option value="2">Хороший уровень</option>
        </select>
    </div>
</div>
<div class="controls ">
    <div class="control-group row-fluid " style="width: 48%;">
        <label class="span12">Дата начала<sup>*</sup> <a href="#" class="tooltipArrDate" rel="tooltip" data-placement="right" data-toggle="tooltip" title="" tabindex="-1"><i class="icon-question-sign"></i></a></label>
        <input class="span12 emArrDate datepicker-form" type="text" maxlength="5" placeholder="ДД.ММ" valid="required">
    </div>
    <div class="control-group row-fluid " style="width: 48%; float: right;">
        <label class="span12">Время <a href="#" id="tooltipArr" rel="tooltip" data-placement="right" data-toggle="tooltip" title="Время приезда к месту проведения конференции (с учётом времени на дорогу от вокзала/аэропорта)" tabindex="-1"><i class="icon-question-sign"></i></a></label>
        <input class="span12 emArrTime" type="time" maxlength="5" tooltip="tooltipArr">
    </div>
    <div class="control-group row-fluid " style="width: 48%;">
        <label class="span12">Дата окончания<sup>*</sup> <a href="#" class="tooltipDepDate" rel="tooltip" data-placement="right" data-toggle="tooltip" title="" tabindex="-1"><i class="icon-question-sign"></i></a></label>
        <input class="span12 emDepDate datepicker-form" type="text" maxlength="5" placeholder="ДД.ММ" valid="required">
    </div>
    <div class="control-group row-fluid " style="width: 48%; float: right;">
        <label class="span12">Время <a href="#" id="tooltipDep" rel="tooltip" data-placement="right" data-toggle="tooltip" title="Время отъезда от места проведения конференции, а не от вокзала" tabindex="-1"><i class="icon-question-sign"></i></a></label>
        <input class="span12 emDepTime" type="time" maxlength="5" tooltip="tooltipDep">
    </div>
</div>
<div class="controls accom-block">
    <div class="control-group row-fluid " style="width: 48%;">
        <label class="span12">Размещение<sup>*</sup></label>
        <select class="span12 emAccom" valid="required">
            <option value='_none_' selected>&nbsp;</option>
            <option value="1">ТРЕБУЕТСЯ</option>
            <option value="0">НЕ ТРЕБУЕТСЯ</option>
        </select>
    </div>
    <div class="control-group row-fluid" style="min-height: 61px; width: 48%; float: right;">
        <label class="span12 emMateLbl" >Разместить с</label>
        <select class="span12 emMate"></select>
    </div>
</div>
<div class="controls parking">
    <div class="control-group row-fluid" style="width: 21%;">
        <label class="span12">Парковка<sup>*</sup> <a href="#" rel="tooltip" data-placement="right" data-toggle="tooltip" title="Только для владельцев автомобилей" tabindex="-1"><i class="icon-question-sign"></i></a></label>
        <select class="span12 emParking" valid="required">
            <option value='_none_'>&nbsp;</option>
            <option value="1">НУЖНА</option>
            <option value="0" selected>НЕ НУЖНА</option>
        </select>
    </div>
    <div class="control-group row-fluid" style="width: 22%; margin-left: 5%;">
        <label class="span12">Номер</label>
        <input class="span12 emAvtomobileNumber" type="text" maxlength="10" valid="required"/>
    </div>
    <div class="control-group row-fluid" style="width: 48%; float: right;">
        <label class="span12">Марка и цвет</label>
        <input class="span12 emAvtomobile" type="text" maxlength="30" valid="required"/>
    </div>
</div>
<div class="controls custom-block">
    <div class="control-group row-fluid">
        <label class="span12 custom-label">
        </label>
        <select class="span12 custom-list">
        </select>
    </div>
</div>
<div class="controls flight-info" style="display: none">
    <div class="control-group row-fluid">
        <label class="span12">Информация о приезде</label>
        <input class="span12 emFlightNumArr" placeholder="Номер поезда или авиарейс и авиакомпания" type="text" maxlength="30" >
    </div>
    <div class="control-group row-fluid">
        <label class="span12">Информация об отъезде</label>
        <input class="span12 emFlightNumDep" placeholder="Номер поезда или авиарейс и авиакомпания" type="text" maxlength="30" >
    </div>
    <div class="control-group row-fluid">
        <label class="span12">Гостиница и количество мест в гостинице.<a href="#" rel="tooltip" data-placement="right" data-toggle="tooltip" title="Пример: 'Sachsen Park Hotel (2-мест.)'" tabindex="-1"><i class="icon-question-sign"></i></a></label>
        <input class="span12 emFlightNote" type="text" maxlength="100">
    </div>
</div>
<div class="controls grpTransport">
    <div class="control-group row-fluid grpTransport">
        <label class="span12" id="lblTransport"><span class="transportText"></span><sup>*</sup>
            <span class="example"></span></label>
        <select class="span12 emTransport" valid="required">
            <option value='_none_' selected>&nbsp;</option>
            <option value="1">ТРЕБУЕТСЯ</option>
            <option value="0">НЕ ТРЕБУЕТСЯ</option>
        </select>
    </div>
</div>
<div class="controls flight-info" style="display: none">
    <div class="control-group row-fluid">
        <label class="span12">Виза</label>
        <select class="span12 emVisa" valid="required">
            <option value="_none_" selected>&nbsp;</option>
            <option value="1">Не требуется</option>
            <option value="2">Уже есть или получу для другой поездки</option>
            <option value="3">Получу для этой поездки</option>
        </select>
    </div>
</div>
<?php if (!$isGuest && !$indexPage) { ?>
<div class="controls">
    <div class="control-group row-fluid contrib-block">
        <label class="span12">Взнос <span class="currency"></span></label>
        <input class="span12 emContrib" type="text" maxlength="4" disabled>
    </div>
    <div class="control-group row-fluid prepaid-block">
        <label class="span12 label-prepaid">Внесено</label>
        <input class="span12 emPrepaid" type="text" maxlength="4">
    </div>
    <div class="control-group row-fluid currency-block">
        <label class="span12 label-currency">Валюта</label>
        <select class="span12 emCurrency">
            <option value='_none_' selected>&nbsp;</option>
            <?php
                foreach (db_getCurrencies() as $key => $val) {
                    echo "<option value='$key'>". htmlspecialchars($val)."</option>";
                }
            ?>
        </select>
    </div>
</div>
<div class="controls service-block">
    <div class="control-group row-fluid" style="width: 48%;">
        <label class="span12">Служение</label>
        <select class="span12 emService">
            <option value='_none_' selected>&nbsp;</option>
            <?php foreach (db_getServices() as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>"; ?>
        </select>
    </div>
    <div class="control-group row-fluid" style="width: 48%; float: right;">
        <label class="span12">Координатор <a href="#" rel="tooltip" data-placement="bottom" data-toggle="tooltip" title="Условия: возраст до 55 лет, здоровье и способность позаботиться о святых" tabindex="-1"><i class="icon-question-sign"></i></a></label>
            <select class="span12 emCoord">
                <option value="0">---</option>
                <option value="1">РЕКОМЕНДУЕТСЯ</option>
            </select>
    </div>
</div>
<div class="controls">
    <div class="control-group row-fluid">
        <label class="span12 emStatusLabel">Статус<sup>*</sup></label>
        <select class="span12 emStatus" valid="required">
            <option value='_none_' selected>&nbsp;</option>
            <?php foreach (db_getStatuses() as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>"; ?>
        </select>
    </div>
</div>
<?php } } ?>
<?php if($noEvent && !$isGuest && !$indexPage){ ?>
<div class="controls school-fields">
    <!--<div class="control-group row-fluid">
        <label class="span12">Школа <span class="emClassLevel"></span></label>
        <input class="span12" type="text" value="Средняя школа" disabled>
    </div>-->
    <div class="control-group row-fluid" style="width: 48%">
        <label class="span12">Год начала учёбы в школе</label>
        <input class="span12 emSchoolStart" type="text" placeholder="ГГГГ" maxlength="4" >
    </div>
    <div class="control-group row-fluid" style="width: 48%; float: right">
        <label class="span12">Год окончания школы</label>
        <input class="span12 emSchoolEnd" type="text" placeholder="ГГГГ" maxlength="4" >
    </div>
    <div class="control-group row-fluid">
        <label class="span12">Примечание о школе</label>
        <input class="span12 emSchoolComment" type="text" maxlength="100" >
    </div>
</div>
<div class="controls college-fields">
    <div class="control-group row-fluid">
        <label class="span12">Учебное заведение <span class="emCourseLevel"></span></label>
        <input class="span12 emCollege" type="text" >
        <i class="fa fa-times fa-lg clear-college"></i>
        <!--<select class="span12 emCollege">
            <option value='_none_' selected>&nbsp;</option>
            <?php //foreach ($colleges as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>"; ?>
        </select>-->
    </div>

    <div class="control-group row-fluid" style="width: 48%">
        <label class="span12">Год поступления</label>
        <input class="span12 emCollegeStart" type="text" placeholder="ГГГГ" maxlength="4" >
    </div>

    <div class="control-group row-fluid" style="width: 48%; float: right">
        <label class="span12">Год окончания</label>
        <input class="span12 emCollegeEnd" type="text" placeholder="ГГГГ" maxlength="4" >
    </div>

    <div class="control-group row-fluid">
        <label class="span12">Примечание об учебном заведении</label>
        <input class="span12 emCollegeComment" type="text" maxlength="100" >
    </div>
</div>
<?php }
if ($noEvent) { ?>
    <div class="controls">
        <div class="control-group row-fluid">
            <label class="span12">Комментарий администратора <span class="example">(виден только администраторам)</span></label>
            <input class="span12 emComment" type="text">
        </div>
    </div>
    <div class="controls">
        <div class="control-group row-fluid" style="width: 48%;">
            <label class="span12">Служащий</label>
            <select id="service_ones_pvom" class="" name="">
              <option value='' selected>&nbsp;</option>
              <?php foreach (db_getServiceonesPvom() as $id => $name) echo "<option value='$id'>".htmlspecialchars ($name)."</option>"; ?>
            </select>
        </div>
        <div class="control-group row-fluid" style="width: 48%; float: right; display: none;">
            <label class="span12">Семестр</label>
            <select id="semestrPvom" class="" name="">
              <option value="" selected>&nbsp;</option>
              <option value="1">Первый</option>
              <option value="2">Второй</option>
              <option value="3">Третий</option>
              <option value="4">Четвёртый</option>
              <option value="5">Пятый</option>
              <option value="6">Шестой</option>
            </select>
        </div>
    </div>
<?php } else  {
if(!$isGuest && !$indexPage){
?>
<div class="controls needAid">
    <div class="control-group row-fluid">
        <label class="span12 emAidLabel">Финансовая помощь</label>
        <select class="emAid span12">
            <option value="0" selected>Не нужна</option>
            <option value="1">Нужна</option>
        </select>
    </div>
    <div class="control-group row-fluid">
        <label class="span12 emFellowshipLabel">Общение об этом с братьями<sup>*</sup> <a href="#" rel="tooltip" data-placement="right" data-toggle="tooltip" tabindex="-1" title="Перед обращением за финансовой помощью вам нужно пообщаться об этом с братьями в вашей местности"><i class="icon-question-sign"></i></a></label>
        <select class="span12 emFellowship">
            <option value="_none_" selected="">&nbsp;</option>
            <option value="0">Не было</option>
            <option value="1">Было</option>
        </select>
    </div>
    <div class="control-group row-fluid">
        <label class="span12 emContrAmountLabel">Не хватает на взнос <a href="#" id="tooltipCountAmount" rel="tooltip" data-placement="right" data-toggle="tooltip" title="Введите сумму в гривнах" tabindex="-1"><i class="icon-question-sign"></i></a></label>
        <input type="text" class="emContrAmount span12">
    </div>
    <div class="control-group row-fluid">
        <label class="span12 emTransAmountLabel">Не хватает на дорогу <a href="#" id="tooltipTransAmount" rel="tooltip" data-placement="right" data-toggle="tooltip" title="Введите сумму в гривнах" tabindex="-1"><i class="icon-question-sign"></i></a></label>
        <input type="text" class="emTransAmount span12">
    </div>
</div>
<div class="controls">
    <div class="control-group row-fluid">
        <label class="span12">Комментарий администратора<span class="example">(виден только администраторам)</span></label>
        <input class="span12 emComment" type="text">
    </div>
</div>
    <?php } ?>
<div class="controls">
    <div class="control-group row-fluid">
        <label class="span12 ">Комментарий участника</label>
        <input class="span12 emUserComment" type="text">
    </div>
</div>
<?php if($indexPage){?>
<div class="controls">
    <div class="control-group row-fluid">
        <input id="terms-use-checkbox" type="checkbox" style="float: left; margin-top: 3px;">
        <label for="terms-use-checkbox" class="" style="margin-left: 5px;">подтверждаю согласие на обработку моих персональных данных</label>
    </div>
</div>
<?php } ?>
<?php } ?>
</div>
<div style="clear: both;"></div>
<script>
//ver 5.0.8
$(document).ready(function(){
  $(".emLocality").change(function() {
      if ($(".emLocality").val() == '001192') {
        $(".emCategory").val('FT');
      } else if ($(".emCategory").val() == 'FT' && $(".emLocality").val() != '001192') {
        $(".emCategory").val('');
      }
  });
});
//end
</script>
