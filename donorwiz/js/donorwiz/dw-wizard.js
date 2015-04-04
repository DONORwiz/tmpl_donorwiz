jQuery(function($) {
	
	var $ = jQuery.noConflict();
	
	$(document).ready(function() {
	
		
		$( "form.dw-wizard" ).each(function( i ) {
  			
			var wizardForm = $(this);
			var wizardFormStateInput = wizardForm.find( 'input[name="jform[state]"]');
		
			var wizardFormTitleInput = wizardForm.find( 'input[name="jform[title]"]');
		
			$('.uk-button.published').click(function() {
			
  				wizardFormStateInput.val('1');

				$('.uk-button.published,.uk-button.unpublished').toggleClass('uk-active');
			});
		
			$('.uk-button.unpublished').click(function() {
			
  				wizardFormStateInput.val('0');

				$('.uk-button.published,.uk-button.unpublished').toggleClass('uk-active');

			});
		
		
			$('.uk-button.trashed').click(function() {
			
				event.preventDefault();
			
				var r = confirm(JText_COM_DONORWIZ_WIZARD_TRASH_CONFIRM);
			
				if (r == true) {

					wizardFormStateInput.val('-2');
					wizardForm.trigger('submit');
				
				}
	
			});
		
		
			wizardFormTitleInput.on('keydown paste cut', function () {
			
				setTimeout(function () {
					$('h1').text( wizardFormTitleInput.val() ); 
  				}, 100);
			});
		
			//Prevent form submit when Enter is pressed
			var wizardFormInputsAndSelects = wizardForm.find( 'input,select' );
		
			wizardFormInputsAndSelects.keypress(function(event) 
			{ 
				return event.keyCode != 13; 
			});	
		
			wizardForm.submit( function( event ) {
				
				event.preventDefault();
				
				dwShowWaitingModal(JText_COM_DONORWIZ_MODAL_PLEASE_WAIT);

				var formData = $(this).serializeArray();
				
				var wizardForm = $(this);

				var editorIsVisible = ( $('.mce-tinymce').css('display') == 'none' ) ? false : true;
			
				if( editorIsVisible == true )
				{
					formData = replaceFormdataItemValue( formData , 'jform[description]' , $('#jform_description_ifr').contents().find( "#tinymce" ).html() )
				}
				else
				{
					$('#jform_description').val($('#jform_description_ifr').contents().find( "#tinymce" ).html());
				}
				
				$.post( wizardForm.attr( 'action' ), formData , function( response ) 
				{
					try {
						var response = jQuery.parseJSON( response );
						var warnings = '';
						
						console.log(response);
										
						if( response.messages && response.messages.warning ){
							
							$.each( response.messages.warning , function( i, warning ){
								warnings +='-'+warning+'<br>';
							});
						
						}
							
						if( ! response.success )
						{
							waitingModal.hide();
							$.UIkit.notify( "<i class=uk-icon-warning></i> " + JText_COM_DONORWIZ_WIZARD_SAVE_FAIL + '<br>' + response.message + '<br>' + warnings , { timeout:5000 } );
							return false;
						}
						
						var redirect = getFormdataItemValue( formData , 'redirect' );
						
						if (redirect != '')
						{
							if( redirect == 'id' )
							{	
								window.location = window.location.href+'?id='+response.data;
							}
							return false;
						}	
												
						window.location = window.location.href;
													
						
					}
					catch( error ){
						
						waitingModal.hide();
						$.UIkit.notify( "<i class=uk-icon-warning></i> " + JText_COM_DONORWIZ_WIZARD_SAVE_FAIL , { timeout:5000 } );
						return false;					
					}
					
				})
				.fail(function() {
					waitingModal.hide();
					$.UIkit.notify( "<i class=uk-icon-warning></i> " + JText_COM_DONORWIZ_WIZARD_SAVE_FAIL , { timeout:2000 } );
				});
			});
		});
	});
	
	function getFormdataItemValue( formdata , name )
	{
		var value = '' ;
		
		$.each( formdata , function( i, item ) {
  					
  			if( item.name == name ){
				value = item.value;
  				return false;
  			}

		});		
		
		return value;
		
	}
	
	function replaceFormdataItemValue( formdata , name , newvalue )
	{	
		$.each( formdata , function( i, item ) {

			if(item.name==name){
				item.value = newvalue;
  			}

		});		
		
		return formdata;
		
	}
	
	function dwShowWaitingModal(text)
	{
		var modalHTML = '';
		
		modalHTML += '<div id="dw-modal" class="uk-modal">';
		modalHTML += '<div class="uk-modal-dialog">';
		modalHTML += '<div class="uk-text-center uk-margin-top"><i class="uk-icon-spinner uk-icon-spin uk-icon-large"></i><h3>'+text+'</h3></div>';
		modalHTML += '</div>';
		modalHTML += '</div>';
		
		$('body').append( $(modalHTML) );
		
		waitingModal = $.UIkit.modal("#dw-modal" , { bgclose : false } ).show();
		
	}
	
});