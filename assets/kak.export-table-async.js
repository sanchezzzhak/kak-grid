(function () {

    $.fn.exportAsyncDropdown = function () {
        const block = $(this);
        const btn = block.find('.export-btn');
        const btnGroup = btn.parent();
        const progress = block.find('.export-progress');

        let url = undefined;
        let type = undefined;
        let salt = undefined;

        btnGroup.on('click', '.export-link-format-async', onClickLink);

        function onClickLink(e) {
            e.preventDefault();
            const link = $(e.target);

            btnGroup.hide();
            progress.show();
            url = link.attr('href');
            type = link.data('type');
            salt = makeSalt();

            $.post(url, {export: 1, type: type, salt: salt})
                .done(fetchStatus)
                .fail(showError);
        }

        function makeSalt() {
            const rand = parseInt(Math.random() * (999999 - 1) + 1);
            return `${Date.now()}${rand}`;
        }

        function fetchStatus() {
            $.post(url, {check: 1, type: type, salt: salt})
                .done(checkStatus)
                .fail(showError);
        }

        function checkStatus(response) {
            if (response.percent !== undefined) {
                const percent = Number(response.percent);

                if (!Number.isNaN(percent)) {
                    progress
                        .find('.progress-bar')
                        .css('width', `${percent}%`)
                        .text(`${percent}%`);
                }
            }

            if (response.state === 'failed') {
                showError(response.message);
                return;
            }

            if (!response.status) {
                setTimeout(fetchStatus, 1000);
                return;
            }

            btnGroup.show();
            progress.hide();
            downloadFile();
        }

        function downloadFile() {
            const input1 = $('<input>');
            input1.attr('type', 'hidden');
            input1.attr('name', yii.getCsrfParam());
            input1.attr('value', yii.getCsrfToken());

            const input2 = $('<input>');
            input2.attr('type', 'hidden');
            input2.attr('name', 'download');
            input2.attr('value', '1');

            const input3 = $('<input>');
            input3.attr('type', 'hidden');
            input3.attr('name', 'type');
            input3.attr('value', type);

            const input4 = $('<input>');
            input4.attr('type', 'hidden');
            input4.attr('name', 'salt');
            input4.attr('value', salt);

            const form = $('<form>');
            form.attr('action', url);
            form.attr('method', 'post');
            form.append(input1);
            form.append(input2);
            form.append(input3);
            form.append(input4);

            $('body').append(form);
            form.submit().remove();
        }

        function showError(err) {
            console.error(err);
            btnGroup.show();
            progress.hide();
            alert(err);
        }
    };

})()
