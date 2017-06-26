/* **** as21 only user profile page **** */

/* **** depends circleDonutChart.js *** */
var circle = new circleDonutChart('circle-dount-chart');
// circle.draw({size:90,end:1000,start:0, maxValue:1100, unitText:'HOURS', titlePosition:"outer-top", outerCircleColor:'#0085c8', innerCircleColor:'#004081'});
circle.draw({size:90,end:total_hours,start:0, maxValue:1500, unitText:'HOURS', titlePosition:"outer-top", outerCircleColor:'#0085c8', innerCircleColor:'#004081'});
