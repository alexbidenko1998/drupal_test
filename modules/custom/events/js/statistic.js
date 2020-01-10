jQuery( document ).ready(function($) {

  var _settings = {
    openingTab: {},
    object: {
      '#day'  : {start: null, end: null},
      '#month': {start: null, end: null},
      '#year' : {start: null, end: null},
      '#full' : {start: null, end: null}
    }
  };
  // day
  var currentDay = new Date();
  var oldDay = new Date(currentDay.getTime());
  oldDay.setMonth( oldDay.getMonth() - 1);
  // month
  var currentMonth = new Date();
  var oldMonth = new Date(currentMonth.getTime());
  oldMonth.setFullYear( oldMonth.getFullYear() - 1);
  // year
  var currentYear = new Date();
  var oldYear = new Date(currentYear.getTime());
  oldYear.setFullYear( oldYear.getFullYear() - 1);

   _settings.object['#day'].start = $("#dayStart").datepicker({
    startDate: oldDay,
    date: oldDay,
    maxDate: currentDay,
    onSelect: function(fd, d, picker) {
      // Ничего не делаем если выделение было снято
      if (!d) return;
      _settings.object['#day'].end.update({
        minDate: d,
      })
    }
  }).data('datepicker');
  _settings.object['#day'].end = $("#dayEnd").datepicker({
    startDate: currentDay,
    minDate: oldDay,
    onSelect: function(fd, d, picker) {
      // Ничего не делаем если выделение было снято
      if (!d) return;
      _settings.object['#day'].start.update({
        maxDate: d,
      })
    }
  }).data('datepicker');

  _settings.object['#day'].start.selectDate(oldDay);
  _settings.object['#day'].end.selectDate(currentDay);

  //////////////////////////////////////////////////////////


  _settings.object['#month'].start = $("#monthStart").datepicker({
    view: "years",
    minView: "months",
    dateFormat: 'MM yyyy',
    maxDate: currentMonth,
    onSelect: function(fd, d, picker) {
      // Ничего не делаем если выделение было снято
      if (!d) return; _settings.object['#month'].end.update({  minDate: d,});
    }
  }).data('datepicker');
  _settings.object['#month'].end = $("#monthEnd").datepicker({
    view: "years",
    minView: "months",
    dateFormat: 'MM yyyy',
    minDate: oldMonth,
    onSelect: function(fd, d, picker) {
      // Ничего не делаем если выделение было снято
      if (!d) return; _settings.object['#month'].start.update({ maxDate: d });
    }
  }).data('datepicker');

  _settings.object['#month'].start.selectDate(oldMonth);
  _settings.object['#month'].end.selectDate(currentMonth);


  _settings.object['#year'].start = $("#yearStart").datepicker({
    view: "years",
    minView: "years",
    dateFormat: 'yyyy',
    maxDate: currentYear,
    onSelect: function(fd, d, picker) {
      // Ничего не делаем если выделение было снято
      if (!d) return; _settings.object['#year'].end.update({  minDate: d,});
    }
  }).data('datepicker');
  _settings.object['#year'].end = $("#yearEnd").datepicker({
    view: "years",
    minView: "years",
    dateFormat: 'yyyy',
    minDate: oldYear,
    onSelect: function(fd, d, picker) {
      // Ничего не делаем если выделение было снято
      if (!d) return; _settings.object['#year'].start.update({ maxDate: d });
    }
  }).data('datepicker');

  _settings.object['#year'].start.selectDate(oldYear);
  _settings.object['#year'].end.selectDate(currentYear);


  function getStatistic(statisticType, periodStart, periodEnd) {
    var type = 'accounts_created';
    var url = document.location.pathname.split('/');
    if( url.hasOwnProperty(2) ){
      type = url[2];
    }

    var xhr = new XMLHttpRequest();
    var url = '/api/getStatistic?statisticType=' + encodeURIComponent(statisticType) +
      "&periodStart=" + encodeURIComponent(periodStart) +
      "&periodEnd="+encodeURIComponent(periodEnd) +
      '&type=' +encodeURIComponent(type);
    // console.log(url);
    xhr.open('GET', url, false);
    xhr.send();
    if (xhr.status != 200) { console.log("xhr.status != 200"); return; }
    // return ( JSON.parse( xhr.responseText )['items'] );
    graphic.setLineChartStatistic( $(statisticType + " .statistic"), JSON.parse( xhr.responseText ) );
    graphic.setPieChartStatistic( $(statisticType + " .pie"), JSON.parse( xhr.responseText ) );

  }


  $(".tab-pane form").submit(function (event) {
    event.preventDefault();
    var type  = $('.nav-tab-statistic .active a').attr('href');
    switch (type){
      case '#day':
        var start = _settings.object['#day'].start.date.getTime();
        var end   = _settings.object['#day'].end.date.getTime();
        getStatistic(type, start, end);
        break;
      case '#month':
        var start = _settings.object['#month'].start.date.getTime();
        var end   = _settings.object['#month'].end.date.getTime();
        getStatistic(type, start, end);
        break;
      case '#year':
        var start = _settings.object['#year'].start.date.getTime();
        var end   = _settings.object['#year'].end.date.getTime();
        getStatistic(type, start, end);
        break;
      case '#full':
        getStatistic(type, 0, 0);
        break;
    }
    console.log(type, start, end);
  });

  $(".nav-tab-statistic a").click(function (event) {
    var tabLink = $(this).attr("href");

    if(!_settings.openingTab.hasOwnProperty(tabLink)){
      _settings.openingTab[tabLink] = true;
      // console.log( $(tabLink + " form"));
      setTimeout(function () {
        $(tabLink + " form").submit();
      }, 500);
    }

  });

  $(".nav-tab-statistic a:first").click();
});

var graphic = {
  // LINE CHARTS
  getLineChartObject: function(info) {
    var obj = {
      chart: { type: 'spline' },
      plotOptions: {
        spline: {
          lineWidth: 4,
          states: { hover: { lineWidth: 5 } },
          marker: { enabled: false }
        }
      },
      title: { text: '' },
      xAxis: {
        categories: [] // ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
      },
      series: []
    }
    obj.title.text = info.title;
    obj.xAxis.categories = info.categories;
    obj.series = [];
    for(var i = 0; i < info.statistic.length; i++ ){
      obj.series[i] = {};
      obj.series[i].name = info.statistic[i].name;
      obj.series[i].data = info.statistic[i].data;
    }
    return obj;
  },

  setLineChartStatistic: function(element, info) {
    var graphicObject = this.getLineChartObject(info);
    element.highcharts( graphicObject );
  },

  // PIE CHARTS
  getPieChartObject: function(info) {
    console.log(info);
    var obj = {
      chart: {type: 'pie'},
      title: {text: ''},
      plotOptions: {
        series: {
          dataLabels: {
            enabled: true,
            format: '{point.name}: {point.y:.0f}'
          }
        }
      },
      series: [{ data: [] }],
    };

    obj.title.text = info.title;
    // obj.xAxis.categories = info.categories;

    //obj.series = [];
    obj.drilldown = {};
    obj.drilldown.series = [];
    for(var i = 0; i < info.statistic.length; i++ ){
      var y = 0;

      var ser = { name: info.statistic[i].name, id: ('group_'+i), data: []};
      for(var ii = 0; ii < info.statistic[i].data.length; ii++ ){
        y +=info.statistic[i].data[ii];
        ser.data[ser.data.length] = [info.categories[i], info.statistic[i].data[ii]];
      }
      obj.series[0].data[i] = {name: info.statistic[i].name, y:y, drilldown: ('group_'+i) };
      obj.drilldown.series[obj.drilldown.series.length] = ser;
    }

    return obj;
  },

  setPieChartStatistic: function(element, info) {
    var graphicObject = this.getPieChartObject(info);
    console.log(graphicObject);
    element.highcharts( graphicObject );
return;
    // Create the chart
    element.highcharts({
      chart: { type: 'pie' },
      title: { text: '' },
      plotOptions: {
        series: {
          dataLabels: {
            enabled: true,
            format: '{point.name}: {point.y:.1f}'
          }
        }
      },

      tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
      },
      series: [{
        name: 'Brands',
        colorByPoint: true,
        data: [{
          name: 'Microsoft Internet Explorer',
          y: 56.33,
          drilldown: 'Microsoft Internet Explorer'
        }, {
          name: 'Chrome',
          y: 24.03,
          drilldown: 'Chrome'
        }, {
          name: 'Firefox',
          y: 10.38,
          drilldown: 'Firefox'
        }, {
          name: 'Safari',
          y: 4.77,
          drilldown: 'Safari'
        }, {
          name: 'Opera',
          y: 0.91,
          drilldown: 'Opera'
        }, {
          name: 'Proprietary or Undetectable',
          y: 0.2,
          drilldown: null
        }]
      }],
      drilldown: {
        series: [{
          name: 'Microsoft Internet Explorer',
          id: 'Microsoft Internet Explorer',
          data: [
            ['v11.0', 24.13],
            ['v8.0', 17.2],
            ['v9.0', 8.11],
            ['v10.0', 5.33],
            ['v6.0', 1.06],
            ['v7.0', 0.5]
          ]
        }, {
          name: 'Chrome',
          id: 'Chrome',
          data: [
            ['v40.0', 5],
            ['v41.0', 4.32],
            ['v42.0', 3.68],
            ['v39.0', 2.96],
            ['v36.0', 2.53],
            ['v43.0', 1.45],
            ['v31.0', 1.24],
            ['v35.0', 0.85],
            ['v38.0', 0.6],
            ['v32.0', 0.55],
            ['v37.0', 0.38],
            ['v33.0', 0.19],
            ['v34.0', 0.14],
            ['v30.0', 0.14]
          ]
        }, {
          name: 'Firefox',
          id: 'Firefox',
          data: [
            ['v35', 2.76],
            ['v36', 2.32],
            ['v37', 2.31],
            ['v34', 1.27],
            ['v38', 1.02],
            ['v31', 0.33],
            ['v33', 0.22],
            ['v32', 0.15]
          ]
        }, {
          name: 'Safari',
          id: 'Safari',
          data: [
            ['v8.0', 2.56],
            ['v7.1', 0.77],
            ['v5.1', 0.42],
            ['v5.0', 0.3],
            ['v6.1', 0.29],
            ['v7.0', 0.26],
            ['v6.2', 0.17]
          ]
        }, {
          name: 'Opera',
          id: 'Opera',
          data: [
            ['v12.x', 0.34],
            ['v28', 0.24],
            ['v27', 0.17],
            ['v29', 0.16]
          ]
        }]
      }
    });

  }
};