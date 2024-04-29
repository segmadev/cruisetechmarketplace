<?php
class Screenshot extends database
{
    function screenshot_template($data = null)
    {
        return '
            <label for="label2" class="p-2 label w-100 rounded rounded-2">
                        <input type="radio" name="label" id="label2">
                        <button type="button" class="btn btn-sm btn-primary">
                            Label: 1
                        </button>
                        <div class="form-group d-flex ">
                            <div class=\'form-group\'>
                                <label for="inputType">Input Type</label>
                                <select class="form-control" name="inputType" id="inputType[]">
                                    <option value="name">Name</option>
                                    <option value="number">Number</option>
                                    <option value="money">Money</option>
                                    <option value="regex">Regex</option>
                                    <option value="static">static</option>
                                </select>
                            </div>
                            <div class=\'form-group\'>
                                <label for="positionX">Position X</label>
                                <input type="number" class="form-control" name="positionX[]" value="10" id="positionX">
                            </div>
                            <div class=\'form-group\'>
                                <label for="positionY">Position Y</label>
                                <input type="number" class="form-control" name="positionY[]" value="10" id="positionY">
                            </div>
                            <div class=\'form-group\'>
                                <label for="color">Color</label>
                                <input type="color" class="form-control" name="color[]"  id="color">
                            </div>
                        </div>
                    </label>
            ';
    }
}
