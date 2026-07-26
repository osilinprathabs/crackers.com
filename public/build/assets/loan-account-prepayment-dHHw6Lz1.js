(function(){let c=null;document.addEventListener("DOMContentLoaded",function(){const e=document.getElementById("prepaymentBtn"),t=new bootstrap.Modal(document.getElementById("prepaymentModal")),n=document.getElementById("prepaymentAmount"),r=document.getElementById("confirmPrepaymentCheck"),o=document.getElementById("confirmPrepaymentBtn");e&&(e.addEventListener("click",function(){c=this.dataset.accountId,this.dataset.accountNumber,h(),u(c),t.show()}),n&&n.addEventListener("input",E(function(){const m=parseFloat(this.value);m&&m>0?y(c,m):d()},500)),r&&r.addEventListener("change",function(){o.disabled=!this.checked}),o&&o.addEventListener("click",function(){x()}))});function u(e){fetch(`/loan-accounts/${e}/prepayment-info`).then(t=>t.json()).then(t=>{g(t),f(t)}).catch(t=>{console.error("Error fetching prepayment info:",t),i("prepaymentEligibilityAlert","danger","Failed to load prepayment information")})}function y(e,t){fetch(`/loan-accounts/${e}/prepayment-info?amount=${t}`).then(n=>n.json()).then(n=>{n.success===!1?(i("prepaymentEligibilityAlert","danger",n.message),d()):b(n)}).catch(n=>{console.error("Error fetching prepayment breakdown:",n)})}function g(e){document.getElementById("prepaymentEligibilityAlert"),e.is_eligible?i("prepaymentEligibilityAlert","success",`✓ Eligible for prepayment (${e.paid_emis_count}/${e.eligibility_months} EMIs completed)`):(i("prepaymentEligibilityAlert","warning",`Not eligible for prepayment yet. Complete ${e.eligibility_months-e.paid_emis_count} more EMI(s)`),document.getElementById("prepaymentAmount").disabled=!0)}function f(e){document.getElementById("minPrepaymentAmount").textContent=`₹${s(e.min_amount)}`,document.getElementById("maxPrepaymentAmount").textContent=`₹${s(e.max_amount)}`}function b(e){document.getElementById("prepaymentOutstanding").textContent=`₹${s(e.outstanding_amount)}`,document.getElementById("prepaymentAmountDisplay").textContent=`₹${s(e.amount)}`,document.getElementById("prepaymentInterest").textContent=`₹${s(e.interest_portion)}`,document.getElementById("prepaymentChargesPercent").textContent=s(e.prepayment_charge_percentage),document.getElementById("prepaymentCharges").textContent=`₹${s(e.prepayment_charge_amount)}`,document.getElementById("prepaymentTotalPayable").textContent=`₹${s(e.total_payable_amount)}`,document.getElementById("prepaymentNewOutstanding").textContent=`₹${s(e.revised_principal)}`}function d(){document.getElementById("prepaymentOutstanding").textContent="₹0.00",document.getElementById("prepaymentAmountDisplay").textContent="₹0.00",document.getElementById("prepaymentInterest").textContent="₹0.00",document.getElementById("prepaymentCharges").textContent="₹0.00",document.getElementById("prepaymentTotalPayable").textContent="₹0.00",document.getElementById("prepaymentNewOutstanding").textContent="₹0.00"}function h(){document.getElementById("prepaymentAmount").value="",document.getElementById("prepaymentAmount").disabled=!1,document.getElementById("prepaymentPaymentMethod").value="cash",document.getElementById("prepaymentReference").value="",document.getElementById("prepaymentRemarks").value="",document.getElementById("confirmPrepaymentCheck").checked=!1,document.getElementById("confirmPrepaymentBtn").disabled=!0,d()}function x(){const e=parseFloat(document.getElementById("prepaymentAmount").value),t=document.getElementById("prepaymentPaymentMethod").value,n=document.getElementById("prepaymentReference").value,r=document.getElementById("prepaymentRemarks").value;if(!e||e<=0){i("prepaymentEligibilityAlert","danger","Please enter a valid prepayment amount");return}const o=document.getElementById("confirmPrepaymentBtn");o.disabled=!0,o.innerHTML='<span class="spinner-border spinner-border-sm me-2"></span>Processing...';const m={amount:e,payment_method:t,payment_reference:n,remarks:r,payment_date:new Date().toISOString().split("T")[0]};fetch(`/loan-accounts/${c}/prepayment`,{method:"POST",headers:{"Content-Type":"application/json","X-CSRF-TOKEN":document.querySelector('meta[name="csrf-token"]').getAttribute("content")},body:JSON.stringify(m)}).then(a=>a.json()).then(a=>{if(a.success){const l=a.data.new_tenure-a.data.old_tenure,B=l<0,p=Math.abs(l),v=B?`<span class="text-success fw-bold"><i class="ri-arrow-down-line"></i> ${p} Months</span>`:l>0?`<span class="text-warning fw-bold"><i class="ri-arrow-up-line"></i> ${p} Months</span>`:'<span class="text-muted">No Change</span>';Swal.fire({title:"",html:`
                            <div class="text-start">
                                <div class="text-center mb-4">
                                    <div class="avatar avatar-xl bg-label-success rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
                                        <i class="ri-checkbox-circle-line fs-1 text-success"></i>
                                    </div>
                                    <h4 class="mb-1">Prepayment Successful!</h4>
                                    <p class="text-muted">The transaction has been processed successfully.</p>
                                </div>

                                <!-- Payment Summary Box -->
                                <div class="bg-label-secondary p-3 rounded mb-4">
                                    <h6 class="fw-bold mb-3 text-uppercase small text-muted">Transaction Summary</h6>
                                    
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-body">Prepayment Amount</span>
                                        <span class="fw-bold">₹${s(a.data.prepayment_amount)}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-body">Charges</span>
                                        <span>₹${s(a.data.prepayment_charge)}</span>
                                    </div>
                                    <hr class="my-3 border-secondary">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold text-heading">Total Paid</span>
                                        <span class="h4 mb-0 text-primary fw-bold">₹${s(a.data.total_payable)}</span>
                                    </div>
                                </div>

                                <!-- Key Metrics Grid -->
                                <div class="row g-3 mb-4">
                                    <div class="col-6">
                                        <div class="p-3 border rounded text-center h-100">
                                            <small class="text-muted d-block mb-1 text-uppercase">New Outstanding</small>
                                            <h5 class="mb-0 text-success fw-bold">₹${s(a.data.new_principal)}</h5>
                                            <small class="text-muted" style="font-size: 0.75rem">Was: ₹${s(a.data.old_principal)}</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-3 border rounded text-center h-100">
                                            <small class="text-muted d-block mb-1 text-uppercase">Tenure Adjustment</small>
                                            <h5 class="mb-0">${v}</h5>
                                            <small class="text-muted" style="font-size: 0.75rem">${a.data.old_tenure} ➔ ${a.data.new_tenure} Months</small>
                                        </div>
                                    </div>
                                </div>

                                ${l>0?`
                                <div class="alert alert-warning py-2 small mb-0 text-start">
                                    <i class="ri-information-line me-1"></i> Tenure adjusted to reflect current outstanding.
                                </div>
                                `:""}
                            </div>
                        `,showConfirmButton:!0,confirmButtonText:"Done",buttonsStyling:!1,width:"500px",padding:"1.5rem",customClass:{popup:"rounded-3",confirmButton:"btn btn-primary btn-lg w-100"}}).then(()=>{window.location.reload()})}else throw new Error(a.message||"Prepayment failed")}).catch(a=>{console.error("Error processing prepayment:",a),Swal.fire({icon:"error",title:"Prepayment Failed",text:a.message||"An error occurred while processing prepayment",confirmButtonText:"OK"}),o.disabled=!1,o.innerHTML='<i class="icon-base ri ri-check-line me-1"></i> Process Prepayment'})}function i(e,t,n){const r=document.getElementById(e);r.className=`alert alert-${t} mb-4 d-flex align-items-center`;const o=t==="success"?"ri-checkbox-circle-line":t==="danger"?"ri-error-warning-line":"ri-information-line";r.innerHTML=`
            <i class="icon-base ri ${o} me-2"></i>
            <span>${n}</span>
        `}function s(e){return e?parseFloat(e).toLocaleString("en-IN",{minimumFractionDigits:2,maximumFractionDigits:2}):"0.00"}function E(e,t){let n;return function(...o){const m=()=>{clearTimeout(n),e.apply(this,o)};clearTimeout(n),n=setTimeout(m,t)}}})();
