<!-- Modal windows for panel-->

<!-- START Modal SPINNER -->
<div id="modalSpinnerPanel" class="modal" style="background-color: rgba(255, 255, 255, 0.3);" >
  <div class="modal-dialog">
    <div class="modal-content" style="border: none; background: none;" >
      <div class="modal-body">
        <div id="saveSpinner" style="margin: 30% 50%; width: 3rem; height: 3rem;" class="spinner-border text-primary"></div>
      </div>
    </div>
  </div>
</div>
<!-- STOP Modal SPINNER -->

<!-- START Modal status statistics contact -->
  <div id="statuWindowStatistic" class="modal hide fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5>Статистика по статусам</h5>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        </div>
        <div class="modal-body">
          <div id="tableStatStatisticsPrint" class="row" style="max-height:400px; overflow-y: auto; padding-left: 7px;">
            <table  style="width:760px;">
              <thead>
                <tr class="tbl-statistics-header"><th class="bg-light" style="text-align: right;">Все</th><th style="text-align: right;">В работе</th><th style="text-align: right;">Недозвон</th><th style="text-align: right;">Ошибка</th>
                  <th style="text-align: right;">Отказ</th><th style="text-align: right;">Заказ</th><th style="text-align: right;">Продолж.</th><th style="text-align: right;">Заверш.</th><th style="text-align: right;">Без статуса</th></tr>
              </thead>
              <tbody id="listStatStatistics">
              </tbody>
            </table>
          </div>
        </div>
          <div class="modal-footer">
            <button id="printStatusesStatistics" class="btn  btn-sm btn-warning">Печать</button>
            <button class="btn  btn-sm btn-secondary" data-dismiss="modal" aria-hidden="true">Закрыть</button>
          </div>
        </div>
      </div>
    </div>
<!-- STOP Modal status statistics contact -->
<!--  Modal windows for panel Stop -->
