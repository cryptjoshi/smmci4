"use strict";
/*
 * ATTENTION: An "eval-source-map" devtool has been used.
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file with attached SourceMaps in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
exports.id = "vendor-chunks/prefix-style";
exports.ids = ["vendor-chunks/prefix-style"];
exports.modules = {

/***/ "(ssr)/./node_modules/prefix-style/index.js":
/*!********************************************!*\
  !*** ./node_modules/prefix-style/index.js ***!
  \********************************************/
/***/ ((module) => {

eval("\nvar div = null;\nvar prefixes = [\n    \"Webkit\",\n    \"Moz\",\n    \"O\",\n    \"ms\"\n];\nmodule.exports = function prefixStyle(prop) {\n    // re-use a dummy div\n    if (!div) {\n        div = document.createElement(\"div\");\n    }\n    var style = div.style;\n    // prop exists without prefix\n    if (prop in style) {\n        return prop;\n    }\n    // borderRadius -> BorderRadius\n    var titleCase = prop.charAt(0).toUpperCase() + prop.slice(1);\n    // find the vendor-prefixed prop\n    for(var i = prefixes.length; i >= 0; i--){\n        var name = prefixes[i] + titleCase;\n        // e.g. WebkitBorderRadius or webkitBorderRadius\n        if (name in style) {\n            return name;\n        }\n    }\n    return false;\n};\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiKHNzcikvLi9ub2RlX21vZHVsZXMvcHJlZml4LXN0eWxlL2luZGV4LmpzIiwibWFwcGluZ3MiOiI7QUFBQSxJQUFJQSxNQUFNO0FBQ1YsSUFBSUMsV0FBVztJQUFFO0lBQVU7SUFBTztJQUFLO0NBQU07QUFFN0NDLE9BQU9DLE9BQU8sR0FBRyxTQUFTQyxZQUFhQyxJQUFJO0lBQ3pDLHFCQUFxQjtJQUNyQixJQUFJLENBQUNMLEtBQUs7UUFDUkEsTUFBTU0sU0FBU0MsYUFBYSxDQUFDO0lBQy9CO0lBRUEsSUFBSUMsUUFBUVIsSUFBSVEsS0FBSztJQUVyQiw2QkFBNkI7SUFDN0IsSUFBSUgsUUFBUUcsT0FBTztRQUNqQixPQUFPSDtJQUNUO0lBRUEsK0JBQStCO0lBQy9CLElBQUlJLFlBQVlKLEtBQUtLLE1BQU0sQ0FBQyxHQUFHQyxXQUFXLEtBQUtOLEtBQUtPLEtBQUssQ0FBQztJQUUxRCxnQ0FBZ0M7SUFDaEMsSUFBSyxJQUFJQyxJQUFJWixTQUFTYSxNQUFNLEVBQUVELEtBQUssR0FBR0EsSUFBSztRQUN6QyxJQUFJRSxPQUFPZCxRQUFRLENBQUNZLEVBQUUsR0FBR0o7UUFDekIsZ0RBQWdEO1FBQ2hELElBQUlNLFFBQVFQLE9BQU87WUFDakIsT0FBT087UUFDVDtJQUNGO0lBRUEsT0FBTztBQUNUIiwic291cmNlcyI6WyJ3ZWJwYWNrOi8vaG9yaXpvbi11aS1jaGFrcmEtbmV4dGpzLy4vbm9kZV9tb2R1bGVzL3ByZWZpeC1zdHlsZS9pbmRleC5qcz9kZWJlIl0sInNvdXJjZXNDb250ZW50IjpbInZhciBkaXYgPSBudWxsXG52YXIgcHJlZml4ZXMgPSBbICdXZWJraXQnLCAnTW96JywgJ08nLCAnbXMnIF1cblxubW9kdWxlLmV4cG9ydHMgPSBmdW5jdGlvbiBwcmVmaXhTdHlsZSAocHJvcCkge1xuICAvLyByZS11c2UgYSBkdW1teSBkaXZcbiAgaWYgKCFkaXYpIHtcbiAgICBkaXYgPSBkb2N1bWVudC5jcmVhdGVFbGVtZW50KCdkaXYnKVxuICB9XG5cbiAgdmFyIHN0eWxlID0gZGl2LnN0eWxlXG5cbiAgLy8gcHJvcCBleGlzdHMgd2l0aG91dCBwcmVmaXhcbiAgaWYgKHByb3AgaW4gc3R5bGUpIHtcbiAgICByZXR1cm4gcHJvcFxuICB9XG5cbiAgLy8gYm9yZGVyUmFkaXVzIC0+IEJvcmRlclJhZGl1c1xuICB2YXIgdGl0bGVDYXNlID0gcHJvcC5jaGFyQXQoMCkudG9VcHBlckNhc2UoKSArIHByb3Auc2xpY2UoMSlcblxuICAvLyBmaW5kIHRoZSB2ZW5kb3ItcHJlZml4ZWQgcHJvcFxuICBmb3IgKHZhciBpID0gcHJlZml4ZXMubGVuZ3RoOyBpID49IDA7IGktLSkge1xuICAgIHZhciBuYW1lID0gcHJlZml4ZXNbaV0gKyB0aXRsZUNhc2VcbiAgICAvLyBlLmcuIFdlYmtpdEJvcmRlclJhZGl1cyBvciB3ZWJraXRCb3JkZXJSYWRpdXNcbiAgICBpZiAobmFtZSBpbiBzdHlsZSkge1xuICAgICAgcmV0dXJuIG5hbWVcbiAgICB9XG4gIH1cblxuICByZXR1cm4gZmFsc2Vcbn1cbiJdLCJuYW1lcyI6WyJkaXYiLCJwcmVmaXhlcyIsIm1vZHVsZSIsImV4cG9ydHMiLCJwcmVmaXhTdHlsZSIsInByb3AiLCJkb2N1bWVudCIsImNyZWF0ZUVsZW1lbnQiLCJzdHlsZSIsInRpdGxlQ2FzZSIsImNoYXJBdCIsInRvVXBwZXJDYXNlIiwic2xpY2UiLCJpIiwibGVuZ3RoIiwibmFtZSJdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///(ssr)/./node_modules/prefix-style/index.js\n");

/***/ })

};
;