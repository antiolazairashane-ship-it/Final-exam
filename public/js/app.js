$.ajaxSetup({headers:{'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')}});
function toast(message,error=false){$('#toast').toggleClass('error',error).text(message).fadeIn(120);setTimeout(()=>$('#toast').fadeOut(160),2800)}
function parseError(xhr){if(xhr.responseJSON){if(xhr.responseJSON.message)return xhr.responseJSON.message;if(xhr.responseJSON.errors)return Object.values(xhr.responseJSON.errors).flat().join(' ')}return'Something went wrong.'}
function ajaxForm(url,method,data,done){$.ajax({url,method,data,success:function(res){toast(res.message||'Saved successfully.');if(done)done(res)},error:function(xhr){toast(parseError(xhr),true)}})}
function ajaxJson(url,method,data,done){$.ajax({url,method,data:JSON.stringify(data),contentType:'application/json',success:function(res){toast(res.message||'Saved successfully.');if(done)done(res)},error:function(xhr){toast(parseError(xhr),true)}})}
function ajaxDelete(url,done){if(!confirm('Delete this record?'))return;ajaxForm(url,'DELETE',{},done)}
$('#logoutBtn').on('click',function(){ajaxForm('/logout','POST',{},function(res){window.location=res.redirect})});
