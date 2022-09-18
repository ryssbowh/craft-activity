var Activity = Garnish.Base.extend({
    adminTable: null,
    $pager: null,
    $filters: null,
    $buttons: null,
    page: 1,

    init: function(settings) {
        this.setSettings(settings);
        //Somehow the menus aren't on the dom when creating menu buttons, need this for the filters to work :
        $.each($('#header .menubtn'), function (i, btn) {
            let menu = $(btn).data('menubtn');
            menu.menu.$container.appendTo(Garnish.$bod);
        });
        this.$filters = $('.activity-filter');
        this.$pager = $('#activity-pager');
        this.$datepickers = $('#header .datepicker');
        this.initAdminTable();
        this.addListener($('#header .btn.delete'), 'click', 'handleDeleteAll');
        this.addListener($('#users-filter a'), 'click', 'handleChangeFilter');
        this.addListener($('#type-filter a'), 'click', 'handleChangeFilter');
        this.addListener($('#per-page a'), 'click', 'handleChangePager');
        this.addListener($('#reset-filters'), 'click', 'handleReset');
        this.initDatePickers();
        this.initPager();
        this.initRecords();
    },

    initRecords: function () {
        this.addListener($('#activity-table .arrow'), 'click', 'handleArrowClick');
    },

    handleReset: function (e) {
        e.preventDefault();
        this.$filters.find('a').removeClass('sel');
        this.$datepickers.val('');
        this.reloadRecords();
    },

    handleArrowClick: function (e) {
        e.preventDefault();
        if ($(e.target).hasClass('down')) {
            $(e.target).removeClass('down');
            $(e.target).closest('tr').next().hide();
        } else {
            $(e.target).addClass('down');
            $(e.target).closest('tr').next().show();
        }
    },

    initDatePickers: function () {
        let _this = this;
        this.$datepickers
            .datepicker(Craft.datepickerOptions)
            .on('change', function(e) {
                _this.reloadRecords();
            });
    },

    initAdminTable: function () {
        this.adminTable = new Craft.AdminTable({
            tableSelector: '#activity-table',
            noItemsSelector: '#noactivity',
            sortable: false,
            nameAttribute: 'name',
            confirmDeleteMessage: Craft.t('activity', 'Are you sure you want to delete this record?'),
            deleteSuccessMessage: Craft.t('activity', 'Record deleted.'),
            deleteFailMessage: Craft.t('activity', 'Couldn’t delete record.'),
            deleteAction: 'activity/activity/delete-log',
            onDeleteItem: () => this.reloadRecords()
        });
    },

    initPager: function () {
        this.addListener($('#activity-pager a.prev-page'), 'click', 'handlePreviousPage');
        this.addListener($('#activity-pager a.next-page'), 'click', 'handleNextPage');
    },

    handleDeleteAll: function () {
        if (confirm(Craft.t('activity', 'Are you sure you want to delete all logs?'))) {
            Craft.postActionRequest('activity/activity/delete-all-logs', {}, (response, textStatus) => {
                if (textStatus === 'success') {
                    if (response.success) {
                        this.adminTable.totalItems = 0;
                        this.adminTable.updateUI();
                        this.$pager.hide();
                        Craft.cp.displayNotice(Craft.t('activity', 'All records deleted'));
                    } else {
                        Craft.cp.displayError(Craft.t('app', "Couldn't delete all records"));
                    }
                }
            });
        }
    },

    handlePreviousPage: function (e) {
        e.preventDefault();
        this.page--;
        this.reloadRecords();
    },

    handleNextPage: function (e) {
        e.preventDefault();
        this.page++;
        this.reloadRecords();
    },

    handleChangeFilter: function (e) {
        var link = $(e.target);
        if (link.hasClass('all')) {
            $(link).closest('ul').find('a').removeClass('sel');
        } else {
            $(link).closest('ul').find('a.all').removeClass('sel');
        }
        link.addClass('sel');
        this.reloadRecords();
    },

    handleChangePager: function (e) {
        var link = $(e.target);
        $(link).closest('ul').find('a').removeClass('sel');
        link.addClass('sel');
        this.page = 1;
        this.reloadRecords();
    },

    changeUrl: function (filters) {
        url = new URL(window.location.href);
        url.pathname = '/' + Craft.cpTrigger + '/activity/' + Craft.pageTrigger + this.page;
        url.search = $.param(filters);
        window.history.pushState({}, "", url.href);
    },

    reloadRecords: function () {
        var filters = this.buildFilters();
        this.changeUrl(filters);
        var url = 'activity/activity/load-logs/' + Craft.pageTrigger + this.page;
        Craft.postActionRequest(url, filters, (response, textStatus) => {
            if (textStatus === 'success') {
                if (response.success) {
                    this.adminTable.$tbody.html(response.logs);
                    this.$pager.html(response.pagination);
                    this.initAdminTable();
                    this.initPager();
                    this.initRecords();
                } else {
                    Craft.cp.displayError(Craft.t('app', "Couldn't load records"));
                }
            }
        });
    },

    buildFilters: function () {
        let filters = {filters: {}};
        $.each(this.$filters, function (i, elem) {
            var selected = $(elem).find('.sel');
            if (!selected.first().hasClass('all')) {
                selection = [];
                $.each(selected, function (i, elem2) {
                    selection.push($(elem2).data('id'));
                });
                filters.filters[$(elem).data('name')] = selection;
            }
        });
        $.each(this.$datepickers, function (i, elem) {
            if ($(elem).val()) {
                filters.filters[$(elem).attr('name')] = $(elem).val();
            }
        });
        filters.perPage = $('#per-page a.sel').html();
        return filters;
    }
});