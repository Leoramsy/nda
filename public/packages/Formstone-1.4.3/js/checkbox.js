/*! formstone v1.4.3 [checkbox.js] 2018-01-25 | GPL-3.0 License | formstone.it */
!function(e){"function"==typeof define&&define.amd?define(["jquery","./core"],e):e(jQuery,Formstone)}(function(e,a){"use strict";function s(a){a.stopPropagation();var s=a.data;e(a.target).is(s.$el)||(a.preventDefault(),s.$el.trigger("click"))}function l(e){var a=e.data,s=a.$el.is(":disabled"),l=a.$el.is(":checked");s||(a.radio?l&&t(e):l?t(e):o(e))}function t(a){a.data.radio&&e('input[name="'+a.data.group+'"]').not(a.data.$el).trigger("deselect"),a.data.$el.trigger(b.focus),a.data.$classable.addClass(r.checked)}function o(e){e.data.$el.trigger(b.focus),e.data.$classable.removeClass(r.checked)}function d(e){e.data.$classable.addClass(r.focus)}function c(e){e.data.$classable.removeClass(r.focus)}var i=a.Plugin("checkbox",{widget:!0,defaults:{customClass:"",toggle:!1,labels:{on:"ON",off:"OFF"},theme:"fs-light"},classes:["element_placeholder","label","marker","flag","radio","focus","checked","disabled","toggle","state","state_on","state_off"],methods:{_construct:function(a){var t=this.closest("label"),i=t.length?t.eq(0):e("label[for="+this.attr("id")+"]"),f=[r.base,a.theme,a.customClass].join(" "),h=[r.label,a.theme,a.customClass].join(" "),u="";a.radio="radio"===this.attr("type"),a.group=this.attr("name"),u+='<div class="'+r.marker+'" aria-hidden="true">',u+='<div class="'+r.flag+'"></div>',a.toggle&&(f+=" "+r.toggle,h+=" "+r.toggle,u+='<span class="'+[r.state,r.state_on].join(" ")+'">'+a.labels.on+"</span>",u+='<span class="'+[r.state,r.state_off].join(" ")+'">'+a.labels.off+"</span>"),a.radio&&(f+=" "+r.radio,h+=" "+r.radio),u+="</div>",a.$placeholder=e('<span class="'+r.element_placeholder+'"></span>'),this.before(a.$placeholder),a.labelParent=i.find(this).length,a.labelClass=h,i.addClass(h),a.labelParent?i.wrap('<div class="'+f+'"></div>').before(u):this.before('<div class=" '+f+'">'+u+"</div>"),a.$checkbox=a.labelParent?i.parents(n.base):this.prev(n.base),a.$marker=a.$checkbox.find(n.marker),a.$states=a.$checkbox.find(n.state),a.$label=i,a.$classable=e().add(a.$checkbox).add(a.$label),this.is(":checked")&&a.$classable.addClass(r.checked),this.is(":disabled")&&a.$classable.addClass(r.disabled),this.appendTo(a.$marker),this.on(b.focus,a,d).on(b.blur,a,c).on(b.change,a,l).on(b.click,a,s).on(b.deselect,a,o),a.$checkbox.on(b.click,a,s)},_destruct:function(e){e.$checkbox.off(b.namespace),e.$marker.remove(),e.$states.remove(),e.$label.removeClass(e.labelClass),e.labelParent?e.$label.unwrap():this.unwrap(),e.$placeholder.before(this),e.$placeholder.remove(),this.off(b.namespace)},enable:function(e){this.prop("disabled",!1),e.$classable.removeClass(r.disabled)},disable:function(e){this.prop("disabled",!0),e.$classable.addClass(r.disabled)},update:function(e){var a=e.$el.is(":disabled"),s=e.$el.is(":checked");a||(s?t({data:e}):o({data:e}))}},events:{deselect:"deselect"}}),n=i.classes,r=n.raw,b=i.events;i.functions});