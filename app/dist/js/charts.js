function donut_chart(
  series,
  labels,
  containerID = "#chart",
  colors = ["var(--bs-danger)", "var(--bs-success)", "#ffaa5fb4"]
) {
  console.log(series);
  const total = series.reduce(
    (accumulator, currentValue) => accumulator + currentValue,
    0
  );
  let dollarUSLocale = Intl.NumberFormat("en-US");
  var formatedAmount = dollarUSLocale.format(total);
  var breakup = {
    color: "#adb5bd",
    series: series,
    labels: labels,
    chart: {
      width: 240,
      type: "donut",
      fontFamily: "Plus Jakarta Sans', sans-serif",
      foreColor: "#adb0bb",
    },
    plotOptions: {
      pie: {
        donut: {
          size: "88%",
          background: "transparent",
          labels: {
            show: true,
            name: {
              show: true,
              offsetY: 7,
            },
            value: {
              show: false,
            },
            total: {
              show: true,
              color: "#7C8FAC",
              fontSize: "20px",
              fontWeight: "600",
              label: formatedAmount,
            },
          },
        },
      },
    },
    stroke: {
      show: false,
    },

    dataLabels: {
      enabled: false,
    },

    legend: {
      show: false,
    },
    colors: colors,

    responsive: [
      {
        breakpoint: 991,
        options: {
          chart: {
            width: 120,
          },
        },
      },
    ],
    tooltip: {
      theme: "dark",
      fillSeriesColor: false,
    },
  };

  var chart = new ApexCharts(document.querySelector(containerID), breakup);
  chart.render();
}

function lines_chart(data,  containerID = "#chart", type= 'fill') {

  var conditon = true;
  var height = 300;
  var opacityFrom = 0.6;
    var opacityTo = 0.3;
  if(type == 'simple') {
    var opacityFrom = 0.3;
    var opacityTo = 0.1;
    var height = 200;
    var conditon = false;
  }
   // Real Time Area Chart -------> AREA CHART
   var options = {
    series: [
      {
        name: "trade profit",
        data: data
      },
    ],
    chart: {
      id: "area-datetime",
      fontFamily: "DM Sans,sans-serif",
      type: "area",
      height: height,
      zoom: {
        autoScaleYaxis: conditon,
      },
      toolbar: {
        show: conditon,
      },

      

    },
    grid: {
      show: conditon,

    },
    colors: ["#615dff"],
    // annotations: {
    //   xaxis: [
    //     {
    //       x: 1693735200000,
    //       borderColor: "#999",
    //       yAxisIndex: 0,
    //       label: {
    //         show: true,
    //         text: "buy",
    //         style: {
    //           color: "#fff",
    //           background: "#6610f2",
    //         },
    //       },
    //     },

    //     {
    //       x: 1693742400000,
    //       borderColor: "#999",
    //       yAxisIndex: 0,
    //       label: {
    //         show: true,
    //         text: "sell",
    //         style: {
    //           color: "#fff",
    //           background: "#6610f2",
    //         },
    //       },
    //     },

    //   ],
    // },
    dataLabels: {
      enabled: true,
      background: {
        enabled: true,
        foreColor: "#fff",
        padding: 4,
        borderRadius: 2,
        borderWidth: 1,
        borderColor: '#fff',
        opacity: 0.9,
        dropShadow: {
          enabled: false,
          top: 1,
          left: 1,
          blur: 1,
          color: '#ffaa5fb4',
          opacity: 0.45
        }
      },
      
    },
    markers: {
      size: 0,
      style: "hollow",
    },
    xaxis: {
      type: "datetime",
      min: data[0][0],
      tickAmount: 2,
      labels: {
        show: conditon,
        style: {
          colors: [
            "#a1aab2",
            "#a1aab2",
            "#a1aab2",
            "#a1aab2",
            "#a1aab2",
            "#a1aab2",
            "#a1aab2",
            "#a1aab2",
            "#a1aab2",
            "#a1aab2",
            "#a1aab2",
            "#a1aab2",
          ],
        },
      },
    },
    yaxis: {
      labels: {
        show: conditon,
        style: {
          colors: [
            "#a1aab2",
            "#a1aab2",
            "#a1aab2",
            "#a1aab2",
            "#a1aab2",
            "#a1aab2",
            "#a1aab2",
          ],
        },
      },
    },
    tooltip: {
      x: {
        show: conditon,
        format: "dd MMM yyyy",
      },
      theme: "dark",
    },
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 0.5,
        opacityFrom: opacityFrom,
        opacityTo: opacityTo,
        stops: [0, 100],
      },
    },
  };

  var chart_area_datetime = new ApexCharts(
    document.querySelector(containerID),
    options
  );
  chart_area_datetime.render();
}
