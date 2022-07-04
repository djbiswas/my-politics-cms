

jQuery.fn.extend({
    createRepeater: function (options = {}) {
        var hasOption = function (optionKey) {
            return options.hasOwnProperty(optionKey);
        };

        var option = function (optionKey) {
            return options[optionKey];
        };

        var generateId = function (string) {
            return string
                    .replace(/\[/g, '_')
                    .replace(/\]/g, '')
                    .toLowerCase();
        };

        var addItem = function (items, key, fresh = true) {
            var itemContent = items;
            var group = itemContent.data("group");
            var item = itemContent;
            var input = item.find('input,select,textarea');

            input.each(function (index, el) {
                var attrName = $(el).data('name');
                var skipName = $(el).data('skip-name');
                if (skipName != true) {
                    $(el).attr("name", group + "[" + key + "]" + "[" + attrName + "]");
                } else {
                    if (attrName != 'undefined') {
                        $(el).attr("name", attrName);
                    }
                }
                if (fresh == true) {
                    $(el).attr('value', '');
                    if ($(el).prop('nodeName') == 'TEXTAREA') {
                        $(el).html('');
                    }
                    if ($(el).prop('nodeName') == 'SELECT') {
                        $(el).find('option').removeAttr("selected");
                    }
                }

                $(el).attr('id', generateId($(el).attr('name')));
                $(el).parent().find('label').attr('for', generateId($(el).attr('name')));
            })

            var itemClone = items;

            /* Handling remove btn */
            var removeButton = itemClone.find('.remove-btn');

            if (key == 0) {
                removeButton.attr('disabled', true);
            } else {
                removeButton.attr('disabled', false);
            }

            removeButton.attr('onclick', '$(this).parents(\'.items\').remove()');

            var newItem = $("<div class='items'>" + itemClone.html() + "<div/>");
            newItem.attr('data-index', key)

            newItem.appendTo(repeater);
        };

        /* find elements */
        var repeater = this;
        var items = repeater.find(".items");
        var key = 0;
        var addButton = repeater.find('.repeater-add-btn');

        items.each(function (index, item) {
            items.remove();
            if (hasOption('showFirstItemToDefault') && option('showFirstItemToDefault') == true) {
                addItem($(item), key, false);
                key++;
            } else {
                //if (items.length > 1) {
                    addItem($(item), key, false);
                    key++;
                //}
            }
        });

        /* handle click and add items */
        addButton.on("click", function () {
            addItem($(items[0]), key, true);
            key++;
        });
    }
});


/* For admin vertical nav sub menu */
var dropdown = document.getElementsByClassName("dropdown-btn");
var i;

for (i = 0; i < dropdown.length; i++) {
    dropdown[i].addEventListener("click", function (e) {
        e.preventDefault();
        this.parentElement.classList.toggle("active");
        var dropdownContent = this.parentElement.nextElementSibling;
        if (dropdownContent.style.display === "block") {
            dropdownContent.style.display = "none";
        } else {
            dropdownContent.style.display = "block";
        }
    });
}
/*var quill = new Quill('.quill-edit', {
 modules: {
 toolbar: [
 [{header: [1, 2, false]}],
 ['bold', 'italic', 'underline'],
 ['image', 'code-block']
 ]
 },
 placeholder: 'Compose an epic...',
 theme: 'snow'  // or 'bubble'
 });*/


$(function ()
{

    /* For Image upload field */

    $('.btn-img-clear').click(function () {
        var parent = $(this).parents('.p-image-sec');
        parent.addClass('remove-img');
        parent.find('.existing_image').val(0);
    });

    /* For repeater field */
    $("#repeater").createRepeater();

    // Quill field logic
    /* $("form.has-quill-field .quill-field-block").each(function () {
     var content = $(this).children('.quill-edit').html();
     console.log(content);
     });
     $("form.has-quill-field").on("submit", function () {
     $("form.has-quill-field .quill-field-block").each(function () {
     var content = $(this).children('.quill-edit .ql-editor').html();
     console.log(content);
     });
     });*/
    // Store data
    var step1 = '{"action":"reguser","step":1,"fieldType":"email", "fieldValue":"nagendrababuganji@gmail.com"}';
    var step2 = '{"action":"reguser","step":2,"fieldType":"email", "fieldValue":"test1@test.com", "validationCode":"164262", "password":"password123"}';
    var step3 = '{"action":"reguser","step":3,"userId": "40","fieldType":"email","penName": {"firstName": "john","lastName": "d"},"firstName": "john","lastName": "doe","address": "abce, 4567, United States","zipCode": "123345","phone": "9949994999","email": "john@doe.com","politcalAffilicaton": "yes","filterContent": "yes","electedOfficial": "yes","publicPosition": "abcd","specialAdministrator": "yes","goodContactNumber": "1234567890"}';
    var updateUserMeta = '{"action":"updateUserMeta", "userId":23, "metaKey":"address", "metaValue":"1-73A, Ddigit, Anumber, US"}';
    var createPost = '{"action":"createPost", "userId": 25 , "politicianId": 63, "postContent":"<p>sadfadsfsadf fasg </p><p>asdf</p><p> </p><p>dfsa</p><p> fdsa</p>"}';
    var updatePass = '{"action":"updatePass", "fieldType":"email", "fieldValue":"nagendrababuganji@gmail.com", "password":"Password1@#"}';
    var updateUserMetaBulk = '{"action":"updateUserMetaBulk", "userId": 25,"penName": {"firstName": "Developer","lastName": "Sr"},"firstName": "Web","lastName": "Dext","address": "P-107 Ground Floor, Regent Mall","zipCode": "38000","politcalAffilicaton": "(D)","filterContent": "Show all content from everyone","electedOfficial": "No","publicPosition": "","specialAdministrator": "Yes","goodContactNumber": "98765412302123"}';
    var vote = '{"action": "vote", "politicianId": 48, "userId": 46, "vote":"down"}';
    var trust = '{"action": "trust", "userId": 49, "respondedId": 48, "trust":"down"}';
    var votingAlerts = '{"action": "votingAlerts", "userId": 48, "politicianId": 58, "alert":"on"}';
    var addReaction = '{"action": "addReaction", "userId": 49, "mType": "post", "mId":16, "reaction":"dislike"}';
   /* $.post("https://www.devuforiawork239.site/cms/api/storedata.php", addReaction, function (data, status) {
        console.log("Data: " + data + "\nStatus: " + status);
    });*/

    // Get data
    var login = '{"action":"login", "fieldType":"email", "fieldValue":"usmantalib202@gmail.com", "password":"Password1@#"}';
    var emailVerifyResend = '{"action":"reguser","step":1,"fieldType":"resendVerificationEmail", "fieldValue":"nagendrababuganji@gmail.com"}';
    var forgotPass = '{"action":"forgotPass", "fieldType":"email", "fieldValue":"nagendrababuganji@gmail.com"}';
    var emailForgotPassVerify = '{"action":"forgotPass", "fieldType":"email", "fieldValue":"nagendrababuganji@gmail.com", "validationCode":"532457"}';
    var getUserMeta = '{"action":"getUserMeta", "userId":23, "metaKey":"pen_name"}';
    var getPosts = '{"action":"getPosts", "politicianId": 63}';
    var getPoliticianCats = '{"action":"getPoliticianCats"}';
    var getPolitician = '{"action":"getPolitician", "id":58, "userId":49}';
    var getPoliticianVotes = '{"action":"getPoliticianVotes", "id":63, "userId":23}';
    var getPoliticians = '{"action":"getPoliticians", "cat":6}';
    var getTrust = '{"action": "getTrust", "userId": 25, "respondedId":23}';
    /*$.post("https://www.devuforiawork239.site/cms/api/getdata.php", getTrust, function (data, status) {
        console.log("Data: " + data + "\nStatus: " + status);
    }); */

});

/* Form default validation script */

(function () {
    'use strict';
    window.addEventListener('load', function () {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function (form) {
            form.addEventListener('submit', function (event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();

/* Delete Politician */
$('.politicians-list').on('click', '.btn-delete-old', function (e) {
    return confirm('Do you really want to delete?');
})