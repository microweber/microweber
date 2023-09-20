<template>

  textDecorationIsBold: {{ textDecorationIsBold }}
  textDecorationIsItalic: {{ textDecorationIsItalic }}
  textDecorationIsUnderline: {{ textDecorationIsUnderline }}
  textDecorationIsStrikethrough: {{ textDecorationIsStrikethrough }}


  v-bind="$attrs"
  fontWeight: {{ fontWeight }}
  <input v-model="fontWeight" placeholder="fontWeight">
  <br>


  fontStyle: {{ fontStyle }}
  <input v-model="fontStyle" placeholder="fontStyle">
  <br>


  lineHeight: {{ lineHeight }}
  <input v-model="lineHeight" placeholder="lineHeight">
  <br>


  fontFamily: {{ fontFamily }}
  <input v-model="fontFamily" placeholder="fontFamily">

  <br>
  color: {{ color }}
  <input v-model="color" placeholder="color">
  <br>
  textAlign: {{ textAlign }}
  <input v-model="textAlign" placeholder="textAlign">


  fontSize: {{ fontSize }}
  <input v-model="fontSize" placeholder="fontSize">
  <br>


  fontWeight: {{ fontWeight }}
  <input v-model="fontWeight" placeholder="fontWeight">
  <br>


  fontStyle: {{ fontStyle }}
  <input v-model="fontStyle" placeholder="fontStyle">
  <br>


  lineHeight: {{ lineHeight }}
  <input v-model="lineHeight" placeholder="lineHeight">
  <br>

</template>


