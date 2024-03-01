var alert_close_button = document.getElementsByClassName('alert-close-button');
for (i = 0; i < close.length; i++) {
  alert_close_button[i].onclick = function () {
    var div = this.parentElement;
    div.style.opacity = '0';
    setTimeout(function () {
      div.style.display = 'none';
    }, 30);
  };
}

function showModalMessage(message, title) {
  setTimeout(function () {
    $('#app-msg-box').find('.modal-title').text(title);
    $('#app-msg-box').find('.modal-body').text(message);
    $('#app-msg-box').show();
  }, 600); //600 milli seconds wait before displaying the box
}

function showSuccess(message, title) {
  hideLoadingBox();
  if (!title) {
    title = 'Success';
  }
  $('#app-msg-box .modal-header').removeClass('bg-primary');
  $('#app-msg-box .modal-header').removeClass('bg-warning');
  $('#app-msg-box .modal-header').addClass('bg-success');
  showModalMessage(message, title);
}

function showError(message, title) {
  if (!title) {
    title = 'Error';
  }
  if (message instanceof Array) {
    var messages = message;
    message = '';
    for (var i = 0; i < messages.length; i++) {
      message += '\u2022 ' + messages[i] + '\n';
    }
  }
  $('#app-msg-box .modal-header').removeClass('bg-primary');
  $('#app-msg-box .modal-header').removeClass('bg-success');
  $('#app-msg-box .modal-header').addClass('bg-warning');
  showModalMessage(message, title);
}

function showInfo(message, title) {
  if (!title) {
    title = 'Info';
  }
  $('#app-msg-box .modal-header').removeClass('bg-warning');
  $('#app-msg-box .modal-header').removeClass('bg-success');
  $('#app-msg-box .modal-header').addClass('bg-primary');
  showModalMessage(message, title);
}

$(document).ready(function () {
  $('#app-msg-box .btn-close').click(function () {
    setTimeout(function () {
      $('#app-msg-box').find('.modal-title').text('');
      $('#app-msg-box').find('.modal-body').text('');
      $('#app-msg-box').hide();
    }, 300);
  });

  var modal = document.getElementById('app-msg-box');
  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function (event) {
    if (event.target == modal) {
      setTimeout(function () {
        $('#app-msg-box').find('.modal-title').text('');
        $('#app-msg-box').find('.modal-body').text('');
        $('#app-msg-box').hide();
      }, 300);
    }
  };
});

function smallMessageBox(msg) {
  if ($('#message_box').length == 0) {
    var msgHTML =
      '<div class="info" id="message_box"><table class="mx-auto"><tr><td id="message">' +
      msg +
      '</td>';
    msgHTML += '<td></td>';
    msgHTML += '</tr></table></div>';
    $('#content').append(msgHTML);
  } else {
    $('#message').html(msg);
  }
  $('#message_box').show();
}

function hideSmallMessageBox() {
  setTimeout(function () {
    $('#message_box').hide();
  }, 100);
}

function loadingBox() {
  smallMessageBox(
    '<b>Sending Request .. <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i></b>',
    -1
  );
}

function hideLoadingBox() {
  hideSmallMessageBox();
}

function createHiddenInputElement(name, value) {
  var hiddenField = document.createElement('input');
  hiddenField.setAttribute('type', 'hidden');
  hiddenField.setAttribute('name', name);
  hiddenField.setAttribute('value', value);
  return hiddenField;
}

function virtualFormSubmit(path, params, method) {
  //Reference: http://ctrlq.org/code/19233-submit-forms-with-javascript
  method = method || 'post';
  var form = document.createElement('form');
  form.setAttribute('method', method);
  form.setAttribute('action', path);
  for (var name in params) {
    if (params.hasOwnProperty(name)) {
      var value = params[name];
      if (value && value instanceof Array) {
        for (var i = 0; i < value.length; i++) {
          hiddenField = createHiddenInputElement(name, value[i]);
          form.appendChild(hiddenField);
        }
      } else {
        hiddenField = createHiddenInputElement(name, value);
        form.appendChild(hiddenField);
      }
    }
  }
  document.body.appendChild(form);
  form.submit();
}

function resetInputForm(form_id) {
  if (form_id !== undefined) {
    $(form_id).each(function () {
      this.reset();
    });
  } else {
    $('.input_form').each(function () {
      this.reset();
    });
  }
}

function redirect(url, msg) {
  showError(msg);
  setTimeout(function () {
    window.location.href = url;
  }, 5000);
}

function redirectToLogin(url) {
  if (
    window.confirm(
      'You have no longer logged in, you will be redirected to Login page..'
    )
  ) {
    window.location.href = url;
  }
}

$(document).ready(function () {
  /**
   * Loading box before ajax requests
   * Reference: https://stackoverflow.com/questions/9157823/show-a-loading-bar-using-jquery-while-making-a-ajax-request
   */
  $(document).ajaxSend(function (e, jqXHR, settings) {
    if (!settings.disableLoading) {
      loadingBox();
    }
  });
  $(document).ajaxComplete(function (e, jqXHR) {
    hideLoadingBox();
  });

  resetInputForm();

  var alert_box_close = document.getElementsByClassName('alert-box-close');
  for (i = 0; i < alert_box_close.length; i++) {
    alert_box_close[i].onclick = function () {
      var div = this.parentElement;
      div.style.opacity = '0';
      setTimeout(function () {
        div.style.display = 'none';
      }, 600);
    };
  }

  $('.select-all-rows').on('click', function (e) {
    var current_table = $(this).closest('table');
    current_table
      .find("input[name='selected_rows[]']")
      .prop('checked', this.checked);
  });
});
