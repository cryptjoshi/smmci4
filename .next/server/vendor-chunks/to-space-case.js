"use strict";
/*
 * ATTENTION: An "eval-source-map" devtool has been used.
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file with attached SourceMaps in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
exports.id = "vendor-chunks/to-space-case";
exports.ids = ["vendor-chunks/to-space-case"];
exports.modules = {

/***/ "(ssr)/./node_modules/to-space-case/index.js":
/*!*********************************************!*\
  !*** ./node_modules/to-space-case/index.js ***!
  \*********************************************/
/***/ ((module, __unused_webpack_exports, __webpack_require__) => {

eval("\nvar clean = __webpack_require__(/*! to-no-case */ \"(ssr)/./node_modules/to-no-case/index.js\");\n/**\n * Export.\n */ module.exports = toSpaceCase;\n/**\n * Convert a `string` to space case.\n *\n * @param {String} string\n * @return {String}\n */ function toSpaceCase(string) {\n    return clean(string).replace(/[\\W_]+(.|$)/g, function(matches, match) {\n        return match ? \" \" + match : \"\";\n    }).trim();\n}\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiKHNzcikvLi9ub2RlX21vZHVsZXMvdG8tc3BhY2UtY2FzZS9pbmRleC5qcyIsIm1hcHBpbmdzIjoiO0FBQ0EsSUFBSUEsUUFBUUMsbUJBQU9BLENBQUM7QUFFcEI7O0NBRUMsR0FFREMsT0FBT0MsT0FBTyxHQUFHQztBQUVqQjs7Ozs7Q0FLQyxHQUVELFNBQVNBLFlBQVlDLE1BQU07SUFDekIsT0FBT0wsTUFBTUssUUFBUUMsT0FBTyxDQUFDLGdCQUFnQixTQUFVQyxPQUFPLEVBQUVDLEtBQUs7UUFDbkUsT0FBT0EsUUFBUSxNQUFNQSxRQUFRO0lBQy9CLEdBQUdDLElBQUk7QUFDVCIsInNvdXJjZXMiOlsid2VicGFjazovL2hvcml6b24tdWktY2hha3JhLW5leHRqcy8uL25vZGVfbW9kdWxlcy90by1zcGFjZS1jYXNlL2luZGV4LmpzPzRmZGEiXSwic291cmNlc0NvbnRlbnQiOlsiXG52YXIgY2xlYW4gPSByZXF1aXJlKCd0by1uby1jYXNlJylcblxuLyoqXG4gKiBFeHBvcnQuXG4gKi9cblxubW9kdWxlLmV4cG9ydHMgPSB0b1NwYWNlQ2FzZVxuXG4vKipcbiAqIENvbnZlcnQgYSBgc3RyaW5nYCB0byBzcGFjZSBjYXNlLlxuICpcbiAqIEBwYXJhbSB7U3RyaW5nfSBzdHJpbmdcbiAqIEByZXR1cm4ge1N0cmluZ31cbiAqL1xuXG5mdW5jdGlvbiB0b1NwYWNlQ2FzZShzdHJpbmcpIHtcbiAgcmV0dXJuIGNsZWFuKHN0cmluZykucmVwbGFjZSgvW1xcV19dKygufCQpL2csIGZ1bmN0aW9uIChtYXRjaGVzLCBtYXRjaCkge1xuICAgIHJldHVybiBtYXRjaCA/ICcgJyArIG1hdGNoIDogJydcbiAgfSkudHJpbSgpXG59XG4iXSwibmFtZXMiOlsiY2xlYW4iLCJyZXF1aXJlIiwibW9kdWxlIiwiZXhwb3J0cyIsInRvU3BhY2VDYXNlIiwic3RyaW5nIiwicmVwbGFjZSIsIm1hdGNoZXMiLCJtYXRjaCIsInRyaW0iXSwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///(ssr)/./node_modules/to-space-case/index.js\n");

/***/ })

};
;