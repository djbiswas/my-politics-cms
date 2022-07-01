<div class="items" data-group="p_pos">
    <div class="item-content">
        <div class="row">
            <div class="col-3">
                <input type="text" class="form-control fieldtest" id="inputName" placeholder="Name" data-name="name" value="{{ (!empty($item['name'])) ? $item['name'] : '' }}">
            </div>
            <div class="col-2">
                <select class="form-control" data-name="res">
                    <option>--Choose--</option>
                    <option <?php echo ($item['res'] == 'Supports') ? 'selected' : ''; ?>>Supports</option>
                    <option <?php echo ($item['res'] == 'Rejects') ? 'selected' : ''; ?>>Rejects</option>
                </select>
            </div>
            <div class="col-5">
                <textarea class="form-control" id="inputPpDescription" placeholder="Description" data-name="ppdescription">{{ (!empty($item['ppdescription'])) ? $item['ppdescription'] : '' }}</textarea>
            </div>
            <div class="pull-right repeater-remove-btn col-2">
                <button id="remove-btn" class="btn btn-danger" onclick="$(this).parents('.items').remove()">
                    Remove
                </button>
            </div>
        </div>
    </div>
</div>