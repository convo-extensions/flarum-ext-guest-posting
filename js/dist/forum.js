module.exports=function(t){var o={};function e(n){if(o[n])return o[n].exports;var r=o[n]={i:n,l:!1,exports:{}};return t[n].call(r.exports,r,r.exports,e),r.l=!0,r.exports}return e.m=t,e.c=o,e.d=function(t,o,n){e.o(t,o)||Object.defineProperty(t,o,{enumerable:!0,get:n})},e.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},e.t=function(t,o){if(1&o&&(t=e(t)),8&o)return t;if(4&o&&"object"==typeof t&&t&&t.__esModule)return t;var n=Object.create(null);if(e.r(n),Object.defineProperty(n,"default",{enumerable:!0,value:t}),2&o&&"string"!=typeof t)for(var r in t)e.d(n,r,function(o){return t[o]}.bind(null,r));return n},e.n=function(t){var o=t&&t.__esModule?function(){return t.default}:function(){return t};return e.d(o,"a",o),o},e.o=function(t,o){return Object.prototype.hasOwnProperty.call(t,o)},e.p="",e(e.s=17)}([function(t,o){t.exports=flarum.core.compat.app},function(t,o){t.exports=flarum.core.compat.extend},function(t,o){t.exports=flarum.core.compat["components/SignUpModal"]},function(t,o,e){"use strict";function n(t,o){t.prototype=Object.create(o.prototype),t.prototype.constructor=t,t.__proto__=o}e.d(o,"a",(function(){return n}))},function(t,o){t.exports=flarum.core.compat["components/IndexPage"]},,function(t,o){t.exports=flarum.core.compat["components/DiscussionPage"]},function(t,o){t.exports=flarum.core.compat.Component},function(t,o){t.exports=flarum.core.compat["components/Button"]},function(t,o){t.exports=flarum.core.compat["helpers/listItems"]},function(t,o){t.exports=flarum.core.compat["components/Badge"]},function(t,o){t.exports=flarum.core.compat["components/CommentPost"]},function(t,o){t.exports=flarum.core.compat["components/DiscussionComposer"]},function(t,o){t.exports=flarum.core.compat["components/ReplyComposer"]},function(t,o){t.exports=flarum.core.compat["utils/DiscussionControls"]},,,function(t,o,e){"use strict";e.r(o),e.d(o,"filterPostMentions",(function(){return I}));var n=e(0),r=e.n(n),s=e(1),u=e(4),a=e.n(u),i=e(6),c=e.n(i),p=e(3),f=e(7),l=e.n(f),d=e(8),g=e.n(d),b=e(2),v=e.n(b),y=e(9),h=e.n(y),x=function(t){function o(){return t.apply(this,arguments)||this}return Object(p.a)(o,t),o.prototype.view=function(){var t="";return t=r.a.forum.attribute("guestPostCount")&&r.a.forum.attribute("guestVoteCount")?r.a.translator.trans("guest-posting.forum.alert.create-account-posts-and-votes",{postCount:r.a.forum.attribute("guestPostCount"),voteCount:r.a.forum.attribute("guestVoteCount")}):r.a.forum.attribute("guestVoteCount")?r.a.translator.trans("guest-posting.forum.alert.create-account-votes-only",{count:r.a.forum.attribute("guestVoteCount")}):r.a.translator.trans("guest-posting.forum.alert.create-account",{count:r.a.forum.attribute("guestPostCount")}),m(".Alert.Alert-info",m(".container",[m("span.Alert-body",t),m("ul.Alert-controls",h()([g.a.component({className:"Button Button--link",onclick:function(){r.a.modal.show(v.a)}},r.a.translator.trans("guest-posting.forum.alert.signup"))]))]))},o}(l.a);function C(t){for(var o=arguments.length,e=new Array(o>1?o-1:0),n=1;n<o;n++)e[n-1]=arguments[n];var s=t.apply(void 0,e);if(!r.a.forum.attribute("guestPostCount")&&!r.a.forum.attribute("guestVoteCount"))return s;var u=x.component();return Array.isArray(s)?(s.unshift(u),s):m("div",[u,s])}var P=e(10),O=e.n(P),j=e(11),A=e.n(j),_=e(12),w=e.n(_),D=e(13),V=e.n(D),G=e(14),S=e.n(G);function I(t){var o=app.store.getById("posts",t.getAttribute("id"));if(o&&o.attribute("guest_username"))return t.setAttribute("discussionid",o.discussion().id()),t.setAttribute("number",o.number()),t.setAttribute("displayname",o.attribute("guest_username")),!0}r.a.initializers.add("guest-posting",(function(){Object(s.override)(a.a.prototype,"hero",C),Object(s.override)(c.a.prototype,"view",C),Object(s.override)(S.a,"replyAction",(function(t,o,e){var n=this;return!r.a.session.user&&this.canReply()?new Promise((function(t){return r.a.composer.composingReplyTo(n)&&!e||r.a.composer.load(V.a,{user:r.a.session.user,discussion:n}),r.a.composer.show(),o&&r.a.viewingDiscussion(n)&&!r.a.composer.isFullScreen()&&r.a.current.get("stream").goToNumber("reply"),t(r.a.composer)})):t(o,e)})),Object(s.override)(a.a.prototype,"newDiscussionAction",(function(t){return!r.a.session.user&&r.a.forum.attribute("canStartDiscussion")?new Promise((function(t){return r.a.composer.load(w.a,{user:r.a.session.user}),r.a.composer.show(),t(r.a.composer)})):t()})),Object(s.extend)(A.a.prototype,"headerItems",(function(t){var o=this.attrs.post.attribute("guest_username");o&&(t.has("user")&&t.remove("user"),t.add("guest-user",m(".PostUser",[m("h3",[m("span.Avatar.PostUser-avatar",o.charAt(0).toUpperCase()),m("span.username",o)]),m("ul.PostUser-badges badges",m("li",O.a.component({type:"guest",icon:"fas fa-user-secret",label:r.a.translator.trans("guest-posting.forum.badge.guest")})))]),100))})),flarum.extensions["fof-polls"]&&flarum.extensions["fof-polls"].components&&flarum.extensions["fof-polls"].components.DiscussionPoll&&(Object(s.override)(flarum.extensions["fof-polls"].components.DiscussionPoll.prototype,"changeVote",(function(t){for(var o=arguments.length,e=new Array(o>1?o-1:0),n=1;n<o;n++)e[n-1]=arguments[n];!r.a.session.user&&this.poll.attribute("canGuestVote")?(r.a.session.user=!0,t.apply(void 0,e),r.a.session.user=null):t.apply(void 0,e)})),Object(s.extend)(flarum.extensions["fof-polls"].components.DiscussionPoll.prototype,"updateData",(function(){var t=this;r.a.session.user||(this.vote(this.votes.find((function(o){return!1===o.user()&&parseInt(o.optionId())===parseInt(t.poll.attribute("guestVoteOption"))}))),this.voted(!!this.vote()))}))),Object(s.extend)(v.a.prototype,"oninit",(function(){this.importGuestContent=!!r.a.forum.attribute("guestPostCount")})),Object(s.extend)(v.a.prototype,"fields",(function(t){var o=this;(r.a.forum.attribute("guestPostCount")||r.a.forum.attribute("guestVoteCount"))&&t.add("guest-posting",m(".Form-group",m("div",m("label.checkbox",[m("input",{type:"checkbox",checked:this.importGuestContent,onchange:function(){o.importGuestContent=!o.importGuestContent},disabled:this.loading}),r.a.forum.attribute("guestVoteCount")?r.a.translator.trans("guest-posting.forum.modal.import-votes",{postCount:r.a.forum.attribute("guestPostCount")||"0",voteCount:r.a.forum.attribute("guestVoteCount")}):r.a.translator.trans("guest-posting.forum.modal.import",{count:r.a.forum.attribute("guestPostCount")})]))))})),Object(s.extend)(v.a.prototype,"submitData",(function(t){this.importGuestContent&&(t.importGuestContent=this.importGuestContent)}))}))}]);
//# sourceMappingURL=forum.js.map