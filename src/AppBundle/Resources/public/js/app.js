var app = angular.module("banking-stats", []);

app.controller('MonthlyCtrl', function($scope, DateRangePickerService, MonthlyService) {


    DateRangePickerService.init();
    MonthlyService.loadData(DateRangePickerService.getUnixStart(), DateRangePickerService.getUnixEnd(), $scope);

    DateRangePickerService.getObject().on('apply.daterangepicker', function(ev, picker) {
        MonthlyService.loadData(DateRangePickerService.getUnixStart(), DateRangePickerService.getUnixEnd(), $scope);
    });

    //Morris.Area({
    //    element: 'morris-area-chart',
    //    data: [{
    //        period: '2010 Q1',
    //        iphone: 2666,
    //        ipad: null,
    //        itouch: 2647
    //    }, {
    //        period: '2010 Q2',
    //        iphone: 2778,
    //        ipad: 2294,
    //        itouch: 2441
    //    }, {
    //        period: '2010 Q3',
    //        iphone: 4912,
    //        ipad: 1969,
    //        itouch: 2501
    //    }, {
    //        period: '2010 Q4',
    //        iphone: 3767,
    //        ipad: 3597,
    //        itouch: 5689
    //    }, {
    //        period: '2011 Q1',
    //        iphone: 6810,
    //        ipad: 1914,
    //        itouch: 2293
    //    }, {
    //        period: '2011 Q2',
    //        iphone: 5670,
    //        ipad: 4293,
    //        itouch: 1881
    //    }, {
    //        period: '2011 Q3',
    //        iphone: 4820,
    //        ipad: 3795,
    //        itouch: 1588
    //    }, {
    //        period: '2011 Q4',
    //        iphone: 15073,
    //        ipad: 5967,
    //        itouch: 5175
    //    }, {
    //        period: '2012 Q1',
    //        iphone: 10687,
    //        ipad: 4460,
    //        itouch: 2028
    //    }, {
    //        period: '2012 Q2',
    //        iphone: 8432,
    //        ipad: 5713,
    //        itouch: 1791
    //    }],
    //    xkey: 'period',
    //    ykeys: ['iphone', 'ipad', 'itouch'],
    //    labels: ['iPhone', 'iPad', 'iPod Touch'],
    //    pointSize: 2,
    //    hideHover: 'auto',
    //    resize: true
    //});
    //
    //Morris.Donut({
    //    element: 'morris-donut-chart',
    //    data: [{
    //        label: "Download Sales",
    //        value: 12
    //    }, {
    //        label: "In-Store Sales",
    //        value: 30
    //    }, {
    //        label: "Mail-Order Sales",
    //        value: 20
    //    }],
    //    resize: true
    //});
    //


});


app.service('MonthlyService', function(DateRangePickerService, AnimationService, StatisticService, $http){

    this.loadData = function(unixStart, unixEnd, scope) {
        $http.post(
            'monthly/data.json',
            {'start': unixStart, 'end': unixEnd }
        )
            .success(function(data) {
                console.log(data);

                scope.incomeSum = data.incomeSum;
                scope.incomeCountRows = data.incomeCountRows;
                scope.outcomeSum = data.outcomeSum;
                scope.outcomeCountRows = data.outcomeCountRows;
                scope.balance = data.balance;
                AnimationService.animateWrapper();

                StatisticService.printBarChart(data.categoryChart);

            }
        );
    }


});


app.service('StatisticService', function() {

    this.printBarChart = function(data) {

        $.each(data, function(type, value){

            var element = 'morris-bar-chart-' + type;
            if(value.data.length > 0) {
                $('#' + element).parent().parent().parent().removeClass('hidden');
                Morris.Bar({
                    element: element,
                    data: value.data,
                    xkey: value.xkey,
                    ykeys: value.ykeys,
                    labels: value.labels,
                    hideHover: 'auto',
                    resize: true
                });
            }
        });
    }
});

app.service('AnimationService', function(){

    this.animateWrapper = function() {
        $('#page-wrapper').removeClass('hidden');
    }
});

app.service('DateRangePickerService', function(){

    var selector = '#reportrange';

    this.getObject = function() {
        return $(selector);
    }
    this.init = function() {
        this.getObject().daterangepicker(
            {
                ranges: {
                    'Heute': [moment(), moment()],
                    'Gestern': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Letzte 7 Tage': [moment().subtract(6, 'days'), moment()],
                    'Letzte 30 Tage': [moment().subtract(29, 'days'), moment()],
                    'Dieser Monat': [moment().startOf('month'), moment().endOf('month')],
                    'Letzter Monat': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment().startOf('month'),
                endDate: moment().endOf('month')
            },
            function(start, end) {
                $(selector + ' span').html(start.format('DD.MM.YYYY') + ' - ' + end.format('DD.MM.YYYY'));
            }
        );
    }

    this.getUnixStart = function()
    {
        return this.getObject().data('daterangepicker').startDate.unix();
    }

    this.getUnixEnd = function()
    {
        return this.getObject().data('daterangepicker').endDate.unix();
    }
});