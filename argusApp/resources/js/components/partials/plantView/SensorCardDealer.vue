<template>
    <div class="sensorViewCard" v-if="sensors">

      <div class="sensorCardChild" v-for="data in sensors" :key="data.name">
          <div class="cardImage" :style="{'background-image': `url(/images/${data.name}.png)`}"></div>
          <p>{{data.reading}}{{data.unit}}</p>
          <p>{{cardHealthScores[data.name]}}</p>
          <div class="meterContainer">
            <div :class="cardHealthScores[data.name] > 20 ? 'activeCell' : 'inactiveCell'"></div>
            <div :class="cardHealthScores[data.name] > 40 ? 'activeCell' : 'inactiveCell'"></div>
            <div :class="cardHealthScores[data.name] > 60 ? 'activeCell' : 'inactiveCell'"></div>
            <div :class="cardHealthScores[data.name] > 80 ? 'activeCell' : 'inactiveCell'"></div>
            <div :class="cardHealthScores[data.name] > 99 ? 'activeCell' : 'inactiveCell'"></div>
          </div>
          <p>{{data.comment}}</p>
          <p>{{data.displayName}}</p>
      </div>
    </div>
</template>

<script>
    export default {
    props: ["cardHealthScores"],

    // data() {
    //   return {
    //     meters: {
    //     }
    //   }
    // },

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
  width: 40rem;
  justify-content: space-between;
  flex-wrap: wrap;
  margin-top: 5rem;
  gap: 2rem;
  .sensorCardChild {
    display: flex;
    border: 1px solid rgb(245, 245, 245);
    border-radius: 1rem;
    flex-direction: column;
    justify-content: center;
    * {
      justify-self: center;
      text-align: center;
    }
  }
}
.meterContainer{
  display: flex;
  justify-content: space-between;
  width: 15rem;
}

.activeCell, .inactiveCell {
  display: inline-block;
  width: 2rem;
  height: 0.5rem;
  border-radius: 0.5rem;
}

.activeCell {
  background-color: #758B66;
}

.inactiveCell {
  background: transparent;
  border: 1px solid gray;
}

.cardImage {
  background-position: center;
  background-repeat: no-repeat;
  // background-size: cover;
  height: 15rem;
  width: 15rem;
  border-radius: 1rem;
}

</style>