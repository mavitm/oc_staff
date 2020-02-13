function staffMove() {
    console.log("stafMove init");
    var $persons = $("#staff-persons"),
        $cols = $(".staff-inner");

    $(".person").draggable({
        cancel: "a.ui-icon",
        revert: "invalid",
        containment: "document",
        helper: "clone",
        cursor: "move"
    });

    $cols.droppable({
        accept: "#staff-persons > .person",
        classes: {
            "ui-droppable-active": "ui-state-highlight"
        },
        drop: function( event, ui ) {
            addPerson(event.target, ui.draggable);
        }
    });

    $persons.droppable({
        accept: ".staff-inner > .person",
        classes: {
            "ui-droppable-active": "ui-state-highlight"
        },
        drop: function( event, ui ) {
            removePerson(event.target, ui.draggable)
        }
    });

     function addPerson($target, $item) {

         if($($target).children().length > 0)
         {
             $.oc.flashMsg({ text: 'only 1 can be added to this field', class: 'error' });
             return false;
         }
         $item.fadeOut(function () {
             $item.appendTo($target);
             $(".person", $target).fadeIn();
         });
         setTimeout(rowsRefactor,1000);
     }

    function removePerson($target, $item) {
         
        $item.fadeOut(function () {
            $item.appendTo($target);
            $(".person", $target).fadeIn();
        });
        setTimeout(rowsRefactor,1000);
    }

    $("#add_staff_row").off("click").on("click", function () {
        addRow();
    });

    $(".row-close").off("click").on("click", function () {
        $(this).parents(".staff-row").remove();
    });

    $(".row-col-select").off("change").on("change",function () {
        var staffRow    = $(this).parents(".staff-row");
        var rowControl  = $(staffRow).find(".control-row");
        var colCount    = $(this).val();

        console.log(colCount);

        $(".staff-col .staff-inner .person", rowControl).each(function () {
            removePerson($persons, $(this));
        });

        $.request('onAddCol', {
            data: {'col':colCount},
            success: function(data) {
                $(rowControl).html('').append(data.data);
                setTimeout(staffMove,1000);
            }
        });
    });

    function addRow(col)
    {
        if(typeof col === "undefined")
        {
            col = 3;
        }

        $.request('onAddRow', {
            data: {'col':col},
            success: function(data) {
                $("#staff_rows").append(data.data);
                setTimeout(staffMove,1000);
            }
        });
    }

    function rowsRefactor() {
        $(".control-row").each(function () {
             var currentRow = $(this);
             var staffIDs = $(this).parents(".staff-row").find(".staffids");
             var persons = [];
             $(staffIDs).val('');
             $(".staff-col .staff-inner .person", currentRow).each(function () {
                persons.push($(this).data('id'));
                $(staffIDs).val(persons.join(','));
             });
        });
    }
}

$(document).ready(function () {
    staffMove();
});

$(document).on('ajaxSetup', function(event, context) {
    context.options.flash = true;
    context.options.loading = $.oc.stripeLoadIndicator;
    context.options.handleErrorMessage = function(message) {
        $.oc.flashMsg({ text: message, class: 'error' })
    };
    context.options.handleFlashMessage = function(message, type) {
        $.oc.flashMsg({ text: message, class: type });
    }
});

$(document).ajaxComplete(function( event, request, settings ) {
    staffMove();
});