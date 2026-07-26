(function(){if(window.adminNotificationsInitialized)return;window.adminNotificationsInitialized=!0;const x=5e3,b=document.documentElement.getAttribute("data-base-url"),r=window.baseUrl||(b?b.replace(/\/+$/,"")+"/":"/"),N=r+"assets/audio/notification-sound.mp3";$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")}});let u=null,c=null,h=null;function A(){c=new Audio(N),c.volume=1;const t=function(){c&&c.play().then(function(){c.pause(),c.currentTime=0,document.removeEventListener("click",t),document.removeEventListener("keydown",t),console.log("Notification audio unlocked successfully.")}).catch(function(e){console.log("Audio unlock failed:",e)})};document.addEventListener("click",t),document.addEventListener("keydown",t)}function T(){c&&(c.currentTime=0,c.play().catch(function(t){console.log("Could not play notification sound:",t)}))}function d(){$.ajax({url:r+"admin/notifications/latest",type:"GET",data:{limit:4},success:function(t){t.success&&(C(t.notifications,t.unread_count),u!==null&&t.unread_count>u&&T(),u=t.unread_count)},error:function(t,e,i){console.error("Failed to load notifications:",i)}})}function C(t,e){const i=$("#notificationBadge"),s=$("#notificationPulse"),o=$("#notificationCount");e>0?(i.show(),s.show(),o.text(e),e>0?$("#notificationDropdown").addClass("has-unread"):$("#notificationDropdown").removeClass("has-unread")):(i.hide(),s.hide(),o.text("0"),$("#notificationDropdown").removeClass("has-unread"));const n=$("#notificationList");if(n.empty(),t.length===0){n.html(`
                <div class="py-5 px-5 text-center text-body-secondary">
                    <span class="d-flex justify-content-center align-items-center mb-3">
                        <span class="avatar-initial rounded-circle bg-label-secondary">
                            <i class="icon-base ri ri-notification-off-line icon-22px"></i>
                        </span>
                    </span>
                    <p class="mb-0">No notifications yet</p>
                </div>
            `);return}t.forEach(function(a){const l=a.is_read,p=l?"":"bg-label-primary",y=$(`
                <div class="list-group-item list-group-item-action ${p} notification-item" 
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
            `);y.on("click",function(){const S=$(this).data("id"),k=$(this).data("link");E(S,function(){k&&(window.location.href=k)})}),n.append(y)})}function E(t,e){$.ajax({url:r+`admin/notifications/${t}/mark-read`,type:"POST",headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},success:function(i){i.success&&(d(),typeof e=="function"&&e())},error:function(){console.error("Failed to mark notification as read")}})}function w(){$.ajax({url:r+"admin/notifications/mark-all-read",type:"POST",headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},success:function(t){t.success&&(console.log("Mark all as read successful"),d(),$("#allNotificationsList").length>0&&typeof window.loadAllNotifications=="function"&&(console.log("Refreshing full page notifications in 300ms..."),setTimeout(function(){window.loadAllNotifications()},300)),typeof window.showAlertToast=="function"?showAlertToast("success",t.message):f("success",t.message))},error:function(){f("error","Failed to mark all as read")}})}function v(){$.ajax({url:r+"admin/notifications/clear-read",type:"POST",headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},success:function(t){t.success&&(console.log("Clear read notifications successful"),d(),$("#allNotificationsList").length>0&&typeof window.loadAllNotifications=="function"&&(console.log("Refreshing full page notifications in 300ms..."),setTimeout(function(){window.loadAllNotifications()},300)),typeof window.showAlertToast=="function"?showAlertToast("success","All notifications cleared"):f("success","All notifications cleared"))},error:function(){f("error","Failed to clear notifications")}})}function f(t,e){const i=document.querySelector(".toast-container")||g(),s="toast-"+Date.now();let o,n;t==="success"?(o="ri-check-line",n="bg-success"):t==="danger"||t==="error"?(o="ri-close-circle-line",n="bg-danger"):t==="warning"?(o="ri-alert-line",n="bg-warning"):t==="info"?(o="ri-information-line",n="bg-info"):(o="ri-error-warning-line",n="bg-danger");const a=`
            <div id="${s}" class="bs-toast toast fade show rounded-5 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" style="border: none;">
                <div class="toast-header ${n} text-white rounded-5 border-0">
                    <i class="icon-base ${o} me-2"></i>
                    <div class="me-auto fw-medium">${e}</div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;i.insertAdjacentHTML("beforeend",a);const l=document.getElementById(s);new bootstrap.Toast(l,{autohide:!0,delay:3e3}).show(),l.addEventListener("hidden.bs.toast",function(){l.remove()})}$(document).ready(function(){A(),d(),h||(h=setInterval(d,x)),$("#markAllRead").off("click").on("click",function(t){t.preventDefault(),t.stopPropagation(),w()}),$("#markAllReadBtn").off("click").on("click",function(t){t.preventDefault(),t.stopPropagation(),w()}),$("#clearAllNotifications").off("click").on("click",function(t){t.preventDefault(),t.stopPropagation(),v()}),$("#clearReadBtn").off("click").on("click",function(t){t.preventDefault(),t.stopPropagation(),v()}),$("#notificationDropdown").off("click.notifications").on("click.notifications",function(){d()}),$("#allNotificationsList").length>0&&I()});let m=null;function I(){console.log("Initializing full page notifications..."),loadAllNotifications(),m=setInterval(function(){console.log("Auto-refreshing notifications..."),loadAllNotifications()},3e4),$(window).on("beforeunload",function(){m&&clearInterval(m)})}window.loadAllNotifications=function(){console.log("Loading all notifications..."),$.ajax({url:r+"admin/notifications/latest",type:"GET",data:{limit:50},success:function(t){console.log("Notifications loaded:",t),t.success&&R(t.notifications)},error:function(){console.error("Failed to load notifications"),$("#allNotificationsList").html('<div class="text-center py-4 text-danger">Failed to load notifications</div>')}})};function R(t){console.log("renderAllNotifications called with",t.length,"notifications");const e=$("#allNotificationsList");if(console.log("Container found:",e.length>0),e.empty(),t.length===0){e.html(`
                <div class="text-center py-5 text-body-secondary">
                    <i class="ri-notification-off-line" style="font-size: 3rem;"></i>
                    <p class="mt-3 mb-0">No notifications yet</p>
                </div>
            `),console.log("No notifications - showing empty state");return}console.log("Rendering",t.length,"notifications..."),t.forEach(function(i){const s=i.is_read,o=s?"":"bg-label-primary",n=$(`
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
            `);n.find(".mark-read-btn").on("click",function(a){a.preventDefault(),a.stopPropagation(),L(i.id)}),e.append(n)}),console.log("Finished rendering all notifications")}function L(t){$.ajax({url:r+`admin/notifications/${t}/mark-read`,type:"POST",headers:{"X-CSRF-TOKEN":$('meta[name="csrf-token"]').attr("content")},success:function(e){e.success&&loadAllNotifications()}})}window.showAlertToast=function(t,e){const i=document.querySelector(".toast-container")||g(),s="toast-"+Date.now();let o,n;t==="success"?(o="ri-check-line",n="bg-success"):t==="danger"||t==="error"?(o="ri-close-circle-line",n="bg-danger"):t==="warning"?(o="ri-alert-line",n="bg-warning"):t==="info"?(o="ri-information-line",n="bg-info"):(o="ri-error-warning-line",n="bg-danger");const a=`
            <div id="${s}" class="bs-toast toast fade show rounded-5 shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" style="border: none;">
                <div class="toast-header ${n} text-white rounded-5 border-0">
                    <i class="icon-base ${o} me-2"></i>
                    <div class="me-auto fw-medium">${e}</div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;i.insertAdjacentHTML("beforeend",a);const l=document.getElementById(s);new bootstrap.Toast(l,{autohide:!0,delay:3e3}).show(),l.addEventListener("hidden.bs.toast",function(){l.remove()})};function g(){const t=document.createElement("div");return t.className="toast-container position-fixed top-0 end-0 p-3",t.style.zIndex="9999",document.body.appendChild(t),t}})();
