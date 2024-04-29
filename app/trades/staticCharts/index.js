//Pseudo code
//Step 1: Define chart properties.
//Step 2: Create the chart with defined properties and bind it to the DOM element.
//Step 3: Add the CandleStick Series.
//Step 4: Set the data and render.

const chartProperties = {
  width:200,
  height:200,
  timeScale:{
    timeVisible:true,
    secondsVisible:false,
  }
}
//Code
const log = console.log;

// const chartProperties = { layout: { textColor: 'black', background: { type: 'solid', color: 'white' } } };

const domElement = document.getElementById('tvchart');
const chart = LightweightCharts.createChart(domElement,chartProperties);
const candleSeries = chart.addAreaSeries({ lineColor: '#2962FF', topColor: '#2962FF', bottomColor: 'rgba(41, 98, 255, 0.28)' });
// const candleSeries = chart.addCandlestickSeries();

// http://127.0.0.1:9665/fetchAPI?endpoint=
fetch(`https://api.binance.com/api/v3/klines?symbol=ETHUSDT&interval=1d&limit=10`)
  .then(res => res.json())
  .then(data => {
    const cdata = data.map(d => {
      return {time:d[0]/1000, value:parseFloat(d[4])}
    });
    candleSeries.setData(cdata);
  })
  .catch(err => log(err))

  return {time:d[0]/1000,open:parseFloat(d[1]),high:parseFloat(d[2]),low:parseFloat(d[3]),close:parseFloat(d[4])}
  


//   const chartOptions = { width: 500, height: 200, layout: { textColor: 'black', background: { type: 'solid', color: 'white' } } };
// const chart = LightweightCharts.createChart(document.getElementById('tvchart'), chartOptions);
// const areaSeries = chart.addAreaSeries({ lineColor: '#2962FF', topColor: '#2962FF', bottomColor: 'rgba(41, 98, 255, 0.28)' });

// const data = [{ value: 0, time: 1642425322 }, { value: 8, time: 1642511722 }, { value: 10, time: 1642598122 }, { value: 20, time: 1642684522 }, { value: 3, time: 1642770922 }, { value: 43, time: 1642857322 }, { value: 41, time: 1642943722 }, { value: 43, time: 1643030122 }, { value: 56, time: 1643116522 }, { value: 46, time: 1643202922 }];

// // areaSeries.setData(data);


// fetch(`https://api.binance.com/api/v3/klines?symbol=ETHUSDT&interval=1h&limit=10`)
//   .then(res => res.json())
//   .then(data => {
//     const cdata = data.map(d => {
//       return {time:d[0]/1000, value:parseFloat(d[4])}
//     });
//     areaSeries.setData(cdata);
//   })
//   .catch(err => log(err))
//   chart.timeScale().fitContent();



