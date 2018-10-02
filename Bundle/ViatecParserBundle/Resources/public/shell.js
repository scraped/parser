function successExecShellCommand(code) {
    $('#' + code + '_output').html('');
    $('#' + code + '_ready').addClass('hidden');
    $('#' + code + '_exec').addClass('hidden');
    $('#' + code + '_running').removeClass('hidden');
    $('#' + code + '_show').removeClass('hidden');
    $('#' + code + '_started-at').html('n/a');
    $('#' + code + '_finished-at').html('n/a');
}

function execShellCommand(code, execUrl, stateUrl) {

    $.get(execUrl, function (res) {
        if (res.status === 'ok') {
            refreshState(code, stateUrl, 1);
            successExecShellCommand(code);
        } else {
            alert(res.error);
        }
    });
}

function refreshState(code, stateUrl, timeout) {
    timeout = timeout || 5; // seconds
    setTimeout(function () {
        $.get(stateUrl, function (state) {
            var elOutput = $('#' + code + '_output');
            elOutput.html(state['output']);
            elOutput.animate({
                scrollTop: elOutput.get(0).scrollHeight
            }, 100);

            $('#' + code + '_started-at').html(state['startedAt']);

            if (state['state'] != 'ready') {
                refreshState(code, stateUrl);
            } else {
                $('#' + code + '_finished-at').html(state['finishedAt']);
                $('#' + code + '_running').addClass('hidden');
                $('#' + code + '_ready').removeClass('hidden');
                $('#' + code + '_exec').removeClass('hidden');
                $('#import').show();
            }
        });
    }, timeout * 1000);
}

function showOutput(code) {
    var $codeOutput = $('#' + code +'_output'); // <pre> element

    if ($codeOutput.parent().hasClass('hidden')) {
        $codeOutput.parent().removeClass('hidden')
    } else {
        $codeOutput.parent().addClass('hidden');
    }
}

