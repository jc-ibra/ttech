<div class="min__h__100 organization__page">
  <div class="container__organization">
    <div class="row">
      <div class="col-12">
        <div class="text-center py-2 container">
          <div class="row text-center">
            <div class="col-md-3">
              <select class="form-select select2area" name="area" id="area">
                <option value="">Todos</option>
                <?php foreach($areas as $area): ?>
                  <option value="<?= $area->id ?>"><?= $area->name ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3">
              <select class="form-select select2" name="department" id="department">
                <option value="">Todos</option>
                <?php foreach($departments as $department): ?>
                  <option value="<?= $department->id ?>"><?= $department->name ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-3">
              <select class="form-select select2general" name="general" id="general">
                <option value="">Selecciona organigrama</option>
                <?php foreach($generalOrganigramas as $organigrama): ?>
                  <option value="<?= $organigrama->id ?>"><?= $organigrama->name ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="card b-s-none pt-4">
    <div id="chart-container" class="min__h__100"></div>
  </div>
</div>
