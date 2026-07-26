(function(){if(window.adminNotificationsInitialized)return;window.adminNotificationsInitialized=!0;const y=5e3,p=document.documentElement.getAttribute("data-base-url"),N=(window.baseUrl||(p?p.replace(/\/+$/,"")+"/":"/"))+"assets/audio/notification-sound.mp3";$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}});let f=null,r=null,b=null;function k(){r=new Audio(N),r.volume=1}function A(){r&&(r.currentTime=0,r.play().catch(function(t){console.log("Could not play notification sound:",t)}))}function c(){$.ajax({url:"/admin/notifications/latest",type:"GET",data:{limit:4},success:function(t){t.success&&(T(t.notifications,t.unread_count),f!==null&&t.unread_count>f&&A(),f=t.unread_count)},error:function(t,n,i){console.error("Failed to load notifications:",i)}})}function T(t,n){const i=$("#notificationBadge"),s=$("#notificationPulse"),o=$("#notificationCount");n>0?(i.show(),s.show(),o.text(n),n>0?$("#notificationDropdown").addClass("has-unread"):$("#notificationDropdown").removeClass("has-unread")):(i.hide(),s.hide(),o.text("0"),$("#notificationDropdown").removeClass("has-unread"));const e=$("#notificationList");if(e.empty(),t.length===0){e.html(`
                <div class="py-5 px-5 text-center text-body-secondary">
                    <span class="d-flex justify-content-center align-items-center mb-3">
                        <span class="avatar-initial rounded-circle bg-label-secondary">
                            <i class="icon-base ri ri-notification-off-line icon-22px"></i>
                        </span>
                    </span>
                    <p class="mb-0">No notifications yet</p>
                </div>
            `);return}t.forEach(function(a){const l=a.is_read,g=l?"":"bg-label-primary",v=$(`
                <div class="list-group-item list-group-item-action ${g} notification-item" 
                     data-id="${a.id}" 
                     data-link="${a.link||""}"
                     style="cursor: pointer;">
                    <div class="d-flex align-items-start p-2">
                        <div class="flex-shrink-0 me-3">
                            <span class="avatar-initial rounded-circle bg-label-${a.badge_color}">
                                <i class="${a.icon} icon-20px"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="mb-0 text-truncate" style="max-width: 200px;">${a.title}</h6>
                                <small class="text-muted text-nowrap ms-2">${a.created_at}</small>
                            </div>
                            <p class="mb-0 small text-truncate">${a.message}</p>
                            ${l?"":'<span class="badge bg-primary badge-sm mt-1">New</span>'}
                        </div>
                    </div>
                </div>
            `);v.on("click",function(){const S=$(this).data("id"),x=$(this).data("link");C(S,function(){x&&(window.location.href=x)})}),e.append(v)})}function C(t,n){$.ajax({url:`/admin/notifications/${t}/mark-read`,type:"POST",headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},success:function(i){i.success&&(c(),typeof n=="function"&&n())},error:function(){console.error("Failed to mark notification as read")}})}function h(){$.ajax({url:"/admin/notifications/mark-all-read",type:"POST",headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},success:function(t){t.success&&(console.log("Mark all as read successful"),c(),$("#allNotificationsList").length>0&&typeof window.loadAllNotifications=="function"&&(console.log("Refreshing full page notifications in 300ms..."),setTimeout(function(){window.loadAllNotifications()},300)),typeof window.showAlertToast=="function"?showAlertToast("success",t.message):d("success",t.message))},error:function(){d("error","Failed to mark all as read")}})}function w(){$.ajax({url:"/admin/notifications/clear-read",type:"POST",headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},success:function(t){t.success&&(console.log("Clear read notifications successful"),c(),$("#allNotificationsList").length>0&&typeof window.loadAllNotifications=="function"&&(console.log("Refreshing full page notifications in 300ms..."),setTimeout(function(){window.loadAllNotifications()},300)),typeof window.showAlertToast=="function"?showAlertToast("success","All notifications cleared"):d("success","All notifications cleared"))},error:function(){d("error","Failed to clear notifications")}})}function d(t,n){const i=document.querySelector(".toast-container")||m(),s="toast-"+Date.now();let o,e;t==="success"?(o="ri-check-line",e="bg-success"):t==="danger"||t==="error"?(o="ri-close-circle-line",e="bg-danger"):t==="warning"?(o="ri-alert-line",e="bg-warning"):t==="info"?(o="ri-information-line",e="bg-info"):(o="ri-error-warning-line",e="bg-danger");const a=`
            <div id="${s}" class="bs-toast toast fade show rounded-5 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" style="border: none;">
                <div class="toast-header ${e} text-white rounded-5 border-0">
                    <i class="icon-base ${o} me-2"></i>
                    <div class="me-auto fw-medium">${n}</div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;i.insertAdjacentHTML("beforeend",a);const l=document.getElementById(s);new bootstrap.Toast(l,{autohide:!0,delay:3e3}).show(),l.addEventListener("hidden.bs.toast",function(){l.remove()})}$(document).ready(function(){k(),c(),b||(b=setInterval(c,y)),$("#markAllRead").off("click").on("click",function(t){t.preventDefault(),t.stopPropagation(),h()}),$("#markAllReadBtn").off("click").on("click",function(t){t.preventDefault(),t.stopPropagation(),h()}),$("#clearAllNotifications").off("click").on("click",function(t){t.preventDefault(),t.stopPropagation(),w()}),$("#clearReadBtn").off("click").on("click",function(t){t.preventDefault(),t.stopPropagation(),w()}),$("#notificationDropdown").off("click.notifications").on("click.notifications",function(){c()}),$("#allNotificationsList").length>0&&I()});let u=null;function I(){console.log("Initializing full page notifications..."),loadAllNotifications(),u=setInterval(function(){console.log("Auto-refreshing notifications..."),loadAllNotifications()},3e4),$(window).on("beforeunload",function(){u&&clearInterval(u)})}window.loadAllNotifications=function(){console.log("Loading all notifications..."),$.ajax({url:"/admin/notifications/latest",type:"GET",data:{limit:50},success:function(t){console.log("Notifications loaded:",t),t.success&&E(t.notifications)},error:function(){console.error("Failed to load notifications"),$("#allNotificationsList").html('<div class="text-center py-4 text-danger">Failed to load notifications</div>')}})};function E(t){console.log("renderAllNotifications called with",t.length,"notifications");const n=$("#allNotificationsList");if(console.log("Container found:",n.length>0),n.empty(),t.length===0){n.html(`
                <div class="text-center py-5 text-body-secondary">
                    <i class="ri-notification-off-line" style="font-size: 3rem;"></i>
                    <p class="mt-3 mb-0">No notifications yet</p>
                </div>
            `),console.log("No notifications - showing empty state");return}console.log("Rendering",t.length,"notifications..."),t.forEach(function(i){const s=i.is_read,o=s?"":"bg-label-primary",e=$(`
                <a href="${i.link||"javascript:void(0);"}" 
                   class="list-group-item list-group-item-action ${o} notification-item" 
                   data-id="${i.id}"
                   data-read="${s}">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0 me-3">
                            <span class="avatar-initial rounded-circle bg-label-${i.badge_color}">
                                <i class="${i.icon} icon-20px"></i>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <h6 class="mb-1">${i.title}</h6>
                                <small class="text-muted">${i.created_at}</small>
                            </div>
                            <p class="mb-1 small">${i.message}</p>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="text-primary fw-semibold text-uppercase small">New</span>
                                ${s?'<span class="text-success small"><i class="ri-check-double-line"></i> Read</span>':'<button class="btn btn-xs btn-label-primary mark-read-btn" data-id="'+i.id+'"><i class="ri-check-line"></i> Mark as Read</button>'}
                            </div>
                        </div>
                    </div>
                </div>
                </a>
            `);e.find(".mark-read-btn").on("click",function(a){a.preventDefault(),a.stopPropagation(),R(i.id)}),n.append(e)}),console.log("Finished rendering all notifications")}function R(t){$.ajax({url:`/admin/notifications/${t}/mark-read`,type:"POST",headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},success:function(n){n.success&&loadAllNotifications()}})}window.showAlertToast=function(t,n){const i=document.querySelector(".toast-container")||m(),s="toast-"+Date.now();let o,e;t==="success"?(o="ri-check-line",e="bg-success"):t==="danger"||t==="error"?(o="ri-close-circle-line",e="bg-danger"):t==="warning"?(o="ri-alert-line",e="bg-warning"):t==="info"?(o="ri-information-line",e="bg-info"):(o="ri-error-warning-line",e="bg-danger");const a=`
            <div id="${s}" class="bs-toast toast fade show rounded-5 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" style="border: none;">
                <div class="toast-header ${e} text-white rounded-5 border-0">
                    <i class="icon-base ${o} me-2"></i>
                    <div class="me-auto fw-medium">${n}</div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;i.insertAdjacentHTML("beforeend",a);const l=document.getElementById(s);new bootstrap.Toast(l,{autohide:!0,delay:3e3}).show(),l.addEventListener("hidden.bs.toast",function(){l.remove()})};function m(){const t=document.createElement("div");return t.className="toast-container position-fixed top-0 end-0 p-3",t.style.zIndex="9999",document.body.appendChild(t),t}})();
