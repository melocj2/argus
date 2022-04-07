<template>
    <div class="sensorViewCard" v-if="sensors">
      <h2 class="invisibleHeader">Sensor Info Card</h2>
      <div class="sensorCardChild" v-for="data in sensors" :key="data.name">
          <div class="cardImage" :style="{'background-image': `url(/images/${data.name}.png)`}"></div>

          <!-- <p class="">{{cardHealthScores[data.name]}}</p> -->
          <div class="sensorTextWrap">
            <h5 class="readingSensorCard">{{data.reading}}{{data.unit}}</h5>
            <div class="meterContainer">
              <div :class="cardHealthScores[data.name] > 20 ? 'activeCell' : 'inactiveCell'"></div>
              <div :class="cardHealthScores[data.name] > 40 ? 'activeCell' : 'inactiveCell'"></div>
              <div :class="cardHealthScores[data.name] > 60 ? 'activeCell' : 'inactiveCell'"></div>
              <div :class="cardHealthScores[data.name] > 80 ? 'activeCell' : 'inactiveCell'"></div>
              <div :class="cardHealthScores[data.name] > 99 ? 'activeCell' : 'inactiveCell'"></div>
            </div>
            <h5 class="commentSensorCard">{{data.comment}}</h5>
          </div>
          <h5 class="sensorType">{{data.displayName}}</h5>
      </div>
    </div>
</template>

<script>
    export default {
    props: ["cardHealthScores"],

    computed: {
      sensors() {

        let readings = this.$store.state.currentReading;

        if(!readings) {
          return false;
        }

        let arr = [];

        let gasComment = readings.gas < 50 ? 'ideal' : readings.gas < 60 ? 'toxic' : readings.gas < 90 ? 'highly toxic' : 'deadly';
        let lightComment = readings.light > 86 ? 'too dark' : readings.light < 5 ? 'too bright' : 'ideal';
        let tempComment = readings.temp < 5 ? 'too cold' : readings.temp < 15 ? 'cold' : readings.temp < 24 ? 'ideal' : readings.temp < 35 ? 'hot' : 'too hot';
        let moistureComment = readings.moisture < 50 ? 'too dry' : readings.moisture > 90 ? 'too damp' : 'ideal';

        //need images for these
        arr.push({ name: 'temp', displayName: 'temperature', reading: readings.temp, unit: '°C', comment: tempComment});
        arr.push({ name: 'light', displayName: 'light', reading: readings.light, unit: '%', comment: lightComment});
        arr.push({ name: 'moisture', displayName: 'moisture', reading: readings.moisture, unit: '%', comment: moistureComment});
        arr.push({ name: 'gas', displayName: 'gas',  reading: readings.gas, unit: '%', comment: gasComment});

        return arr;

      }
    }
}
</script>

<style lang="scss">
 @import "../../../.././sass/variables/breakpoints.scss";
    @import "../../../.././sass/variables/fonts.scss";
    @import "../../../.././sass/variables/colors.scss";
    @import "../../../.././sass/mixins/all-mixins.scss";

.sensorViewCard {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  width: 16rem;
  .sensorCardChild {
    display: grid;
    grid-template-columns: repeat(10, 1fr);
    grid-template-rows: repeat(8, 1fr);
    border-radius: 10px;
    background-color: $white;
    height: 12rem;
    margin-bottom: 2em;
    @include border;
    .cardImage {
      border-radius: 10px 0 0 0;
      grid-row: 1/7;
      grid-column: 1/6;
      align-self: center;
      justify-self: center;
      background-position: center;
      background-repeat: no-repeat;
      background-size: contain;
      width: 70%;
      height: 70%;
    }
    .sensorTextWrap {
      border-radius: 0 10px 0 0;
      grid-row: 1/7;
      grid-column: 6/11;
      align-self: center;
      justify-self: center;
      width: 80%;
      height: 70%;
      display: flex;
      flex-direction: column;
      justify-content: space-around;
      .readingSensorCard, .commentSensorCard {
        text-align: center;
        color: $purple;
      }
      .meterContainer{
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-around;

        .activeCell, .inactiveCell {
          display: inline-block;
          width: 1rem;
          height: 0.5rem;
          border-radius: 0.5rem;
          margin: 0 0.2rem;
        }
        .activeCell {
          background-color: $green;
        }

        .inactiveCell {
          background-color: $greyGreen;
        }
      }
    }
    .sensorType {
      grid-row: 7/9;
      grid-column: 1/11;
      width: 100%;
      height: 100%;
      border-radius: 0 0 10px 10px;
      align-self: center;
      justify-self: center;
      background-color: $purple;
      color: $white;
      text-align: center;
      display: flex;
      align-items: center;
      justify-content: center;
    }
  }
}
@media (min-width: $tablet-break) {

.sensorViewCard {
  display: flex;
  flex-direction: row;
  justify-content: space-between;
  flex-wrap: wrap;
  width: 32rem;
  .sensorCardChild {
    display: grid;
    grid-template-columns: repeat(10, 1fr);
    grid-template-rows: repeat(8, 1fr);
    border-radius: 10px;
    background-color: $white;
    height: 12rem;
    margin-bottom: 2em;
    @include border;
    .cardImage {
      border-radius: 10px 0 0 0;
      grid-row: 1/7;
      grid-column: 1/6;
      align-self: center;
      justify-self: center;
      background-position: center;
      background-repeat: no-repeat;
      background-size: contain;
      width: 70%;
      height: 70%;
    }
    .sensorTextWrap {
      border-radius: 0 10px 0 0;
      grid-row: 1/7;
      grid-column: 6/11;
      align-self: center;
      justify-self: center;
      width: 80%;
      height: 70%;
      display: flex;
      flex-direction: column;
      justify-content: space-around;
      .readingSensorCard, .commentSensorCard {
        text-align: center;
        color: $purple;
      }
      .meterContainer{
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: space-around;

        .activeCell, .inactiveCell {
          display: inline-block;
          width: 1rem;
          height: 0.5rem;
          border-radius: 0.5rem;
          margin: 0 0.2rem;
        }
        .activeCell {
          background-color: $green;
        }

        .inactiveCell {
          background-color: $greyGreen;
        }
      }
    }
    .sensorType {
      grid-row: 7/9;
      grid-column: 1/11;
      width: 100%;
      height: 100%;
      border-radius: 0 0 10px 10px;
      align-self: center;
      justify-self: center;
      background-color: $purple;
      color: $white;
      text-align: center;
      display: flex;
      align-items: center;
      justify-content: center;
    }
  }
}
}

 @media (min-width: $tablet-break) {
    .sensorViewCard {
    .sensorCardChild {
      display: grid;
      grid-template-columns: repeat(10, 1fr);
      grid-template-rows: repeat(8, 1fr);
      border-radius: 10px;
      background-color: $white;
      height: 10rem;
      width: 15rem;
    }
  }
 }

  @media (min-width: $desktop-break) {
    .sensorViewCard {
      width: 52rem;
      gap: 1em;
    .sensorCardChild {
      display: grid;
      grid-template-columns: repeat(10, 1fr);
      grid-template-rows: repeat(8, 1fr);
      border-radius: 10px;
      background-color: $white;
      height: 15rem;
      width: 25rem;
      align-self: center;
      justify-self: center;
      margin-bottom: 0;
    }
  }
 }


</style>