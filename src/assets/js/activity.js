var Activity = Garnish.Base.extend({
  adminTable: null,
  hotReloadTimeout: null,
  $pager: null,
  $filters: null,
  $buttons: null,
  modal: null,
  modalContent: null,
  preventReload: false,
  page: 1,

  init: function (settings) {
    this.setSettings(settings);
    //Somehow the menus aren't on the dom when creating menu buttons, need this for the filters to work :
    $.each($("#header .menubtn"), function (i, btn) {
      let menu = $(btn).data("menubtn");
      menu.menu.$container.appendTo(Garnish.$bod);
    });
    this.$modal = $("#activity-field-modal");
    this.$filtersModal = $("#activity-filters-modal");
    this.modal = new Garnish.Modal("#activity-field-modal", {
      autoShow: false,
    });
    this.filtersModal = new Garnish.Modal("#activity-filters-modal", {
      autoShow: false,
      triggerElement: $("#header .btn.more-filters"),
    });
    this.$filters = $(".activity-filter");
    this.$pager = $("#activity-pager");
    this.$datepickers = $("#header .datepicker");
    this.$hotReload = $("#header .js-hot-reload");
    this.$moreFiltersCount = $("#header .more-filters-count");
    this.$userPicker = $("#user-picker").data("elementSelect");
    this.initAdminTable();
    this.addListener($("#header .btn.delete"), "click", "handleDeleteAll");
    this.addListener($("#header .btn.more-filters"), "click", () => {
      this.filtersModal.show();
    });
    this.addListener(
      $(".menu.activity-filter a"),
      "click",
      "handleChangeFilter"
    );
    this.addListener($(".activity-per-page a"), "click", "handleChangePager");
    this.addListener($("#reset-filters"), "click", "handleReset");
    this.addListener($("#export-menu a"), "click", "handleExport");
    this.initDatePickers();
    this.initPager();
    this.initRecords();
    this.initHotReload();
    this.initModal();
    this.initFiltersModal();
  },

  handleExport: function (e) {
    e.preventDefault();
    var filters = this.buildFilters();
    delete filters.perPage;
    filters.type = $(e.target).data("handle");
    window.open(Craft.getCpUrl("export-activity-logs", filters));
  },

  initModal: function () {
    this.$modal.find(".js-close").click(() => {
      this.modal.hide();
    });
    this.$modal.find(".js-switch").click(() => {
      this.switchModalSource();
    });
  },

  initFiltersModal: function () {
    this.$filtersModal.find(".js-close").click(() => {
      this.filtersModal.hide();
    });
    this.$filtersModal.find(".elementselect").each((index, elem) => {
      let select = $(elem).data("elementSelect");
      select.on("selectElements", () => {
        this.reloadRecords();
      });
      select.on("removeElements", () => {
        this.reloadRecords();
      });
    });
  },

  initRecords: function () {
    this.addListener($("#activity-table .arrow"), "click", "handleArrowClick");
    this.addListener(
      $("#activity-table .js-view-field-value"),
      "click",
      "loadFieldValue"
    );
  },

  handleReset: function (e) {
    e.preventDefault();
    this.preventReload = true;
    this.$filters.find("a").removeClass("sel");
    this.$filters.find("a.all").addClass("sel");
    this.$datepickers.val("");
    this.$filtersModal.find(".elementselect").each((index, elem) => {
      let select = $(elem).data("elementSelect");
      select.removeElement(select.getElements());
    });
    this.preventReload = false;
    this.reloadRecords();
  },

  handleArrowClick: function (e) {
    e.preventDefault();
    if ($(e.target).hasClass("down")) {
      $(e.target).removeClass("down");
      $(e.target).closest("tr").next().hide();
    } else {
      $(e.target).addClass("down");
      $(e.target).closest("tr").next().show();
    }
  },

  switchModalSource: function () {
    let link = this.$modal.find(".js-switch");
    let currentLabel = link.html();
    let newLabel = link.data("label");
    let content = this.$modal.find(".content");
    if (content.hasClass("src")) {
      content.html(this.modalContent);
      content.removeClass("src");
    } else {
      content.html(
        '<script type="text/plain" style="display: block">' +
          this.modalContent +
          "</script>"
      );
      content.addClass("src");
    }
    link.data("label", currentLabel);
    link.html(newLabel);
  },

  loadFieldValue: function (e) {
    e.preventDefault();
    let data = {
      key: $(e.target).data("key"),
      id: $(e.target).data("id"),
    };
    Craft.postActionRequest(
      "activity/activity/field-value",
      data,
      (response, textStatus) => {
        if (textStatus === "success") {
          if (response.success) {
            this.$modal.find(".content").html(response.data);
            this.modalContent = response.data;
            if ($(e.target).data("hassource")) {
              this.$modal.find(".js-switch").show();
            } else {
              this.$modal.find(".js-switch").hide();
            }
            this.modal.show();
          } else {
            Craft.cp.displayError(Craft.t("app", "Couldn't load field value"));
          }
        }
      }
    );
  },

  initDatePickers: function () {
    let _this = this;
    this.$datepickers
      .datepicker(Craft.datepickerOptions)
      .on("change", function (e) {
        _this.reloadRecords();
      });
  },

  initHotReload: function () {
    let _this = this;
    this.$hotReload.click(function (e) {
      if ($(e.target).is(":checked")) {
        _this.startHotReload();
      } else {
        _this.stopHotReload();
      }
    });
  },

  initAdminTable: function () {
    this.adminTable = new Craft.AdminTable({
      tableSelector: "#activity-table",
      noItemsSelector: "#noactivity",
      sortable: false,
      nameAttribute: "name",
      confirmDeleteMessage: Craft.t(
        "activity",
        "Are you sure you want to delete this record?"
      ),
      deleteSuccessMessage: Craft.t("activity", "Record deleted."),
      deleteFailMessage: Craft.t("activity", "Couldn’t delete record."),
      deleteAction: "activity/activity/delete-log",
      onDeleteItem: () => this.reloadRecords(),
    });
  },

  initPager: function () {
    this.addListener(
      $("#activity-pager a.prev-page"),
      "click",
      "handlePreviousPage"
    );
    this.addListener(
      $("#activity-pager a.next-page"),
      "click",
      "handleNextPage"
    );
  },

  startHotReload: function () {
    if (this.hotReloadTimeout) {
      return;
    }
    let _this = this;
    this.hotReloadTimeout = setInterval(function () {
      _this.reloadRecords();
    }, 5000);
  },

  stopHotReload: function () {
    if (this.hotReloadTimeout) {
      clearInterval(this.hotReloadTimeout);
    }
  },

  handleDeleteAll: function () {
    if (
      confirm(Craft.t("activity", "Are you sure you want to delete all logs?"))
    ) {
      Craft.postActionRequest(
        "activity/activity/delete-all-logs",
        {},
        (response, textStatus) => {
          if (textStatus === "success") {
            if (response.success) {
              this.adminTable.totalItems = 0;
              this.adminTable.updateUI();
              this.$pager.hide();
              Craft.cp.displayNotice(
                Craft.t("activity", "All records deleted")
              );
            } else {
              Craft.cp.displayError(
                Craft.t("app", "Couldn't delete all records")
              );
            }
          }
        }
      );
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
    if (link.hasClass("all")) {
      $(link).closest("ul").find("a").removeClass("sel");
    } else {
      $(link).closest("ul").find("a.all").removeClass("sel");
    }
    link.addClass("sel");
    this.reloadRecords();
  },

  handleChangePager: function (e) {
    var link = $(e.target);
    $(link).closest("ul").find("a").removeClass("sel");
    link.addClass("sel");
    this.page = 1;
    this.reloadRecords();
  },

  changeUrl: function (filters) {
    url = new URL(window.location.href);
    url.pathname =
      "/" + Craft.cpTrigger + "/activity/" + Craft.pageTrigger + this.page;
    url.search = $.param(filters);
    window.history.pushState({}, "", url.href);
  },

  reloadRecords: function () {
    if (this.preventReload) {
      return;
    }
    var filters = this.buildFilters();
    this.changeUrl(filters);
    var url = "activity/activity/load-logs/" + Craft.pageTrigger + this.page;
    Craft.postActionRequest(url, filters, (response, textStatus) => {
      if (textStatus === "success") {
        if (response.success) {
          this.adminTable.$tbody.html(response.logs);
          this.$pager.html(response.pagination);
          this.initAdminTable();
          this.initPager();
          this.initRecords();
        } else {
          Craft.cp.displayError(Craft.t("app", "Couldn't load records"));
        }
      }
    });
  },

  buildFilters: function () {
    let filters = { filters: {} };
    $.each(this.$filters, function (i, elem) {
      var selected = $(elem).find(".sel");
      if (!selected.first().hasClass("all")) {
        selection = [];
        $.each(selected, function (i, elem2) {
          selection.push($(elem2).data("id"));
        });
        filters.filters[$(elem).data("name")] = selection;
      }
    });
    $.each(this.$datepickers, function (i, elem) {
      if ($(elem).val()) {
        filters.filters[$(elem).attr("name")] = $(elem).val();
      }
    });
    let count = 0;
    this.$filtersModal.find(".elementselect").each((index, elem) => {
      let select = $(elem).data("elementSelect");
      let name = $(elem).prev().attr("name");
      if (select.getSelectedElementIds().length > 0) {
        filters.filters[name] = select.getSelectedElementIds();
        count += select.getSelectedElementIds().length;
      }
    });
    filters.perPage = $(".activity-per-page a.sel").html();
    this.$moreFiltersCount.html(count > 0 ? "(" + count + ")" : "");
    return filters;
  },
});
