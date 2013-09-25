(function($) {
	$(document)
			.ready(
					function() {

						$.ajaxSetup({
							cache : false
						});

						var pageLoaderID = 42;

						$('#map')
						.vectorMap(
										{

											map : 'de_merc_en',
											backgroundColor: 'white',
											markers: [{'latLng': [51.3288,12.371], 'name': "Leipzig"}],
											regionStyle: {
												  initial: {
													    fill: 'white',
													    "fill-opacity": 1,
													    stroke: 'grey',
													    "stroke-width": 1,
													    "stroke-opacity": 1
													  },
													  hover: {
													    "fill-opacity": 0.8
													  },
													  selected: {
													    fill: 'yellow'
													  },
													  selectedHover: {
													  }
													},

											onRegionClick : function(event,
													code) {

												var post_id;

												switch (code) {

												case "DE":

													post_id = 30;

													break;

												case "US":

													post_id = 54;

													break;

												case "FR":

													post_id = 36;

													break;

												case "ES":

													post_id = 56;

													break;

												default:

													post_id = false;

													break;

												}

												console.log('Country code: '
														+ code + ' Post ID: '
														+ post_id);

												if (post_id !== false) {

													$("#mapData").html(
															"Loading data...");

													$("#mapData")
															.load(
																	"http://tests.andremeyering.de/jvectormap/wp/?page_id="
																			+ pageLoaderID,
																	{
																		id : post_id
																	});

												} else {

													$("#mapData")
															.html(
																	"<p><strong>No Country data avaiable!<br></strong>Avaiable countries: Germany, USA, France and Spain.</p>");

												}
												return false;

											}

										});

					});

})(jQuery);
