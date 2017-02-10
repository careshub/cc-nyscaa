<?php
/**
 * Template tag for outputting the NYSCAA Poverty Report.
 *
 * Community Commons NYSCAA
 *
 * @package   Community_Commons_NYSCAA
 * @author    Yan Barnett
 * @license   GPL-2.0+
 * @link      http://www.communitycommons.org
 * @copyright 2013 Community Commons
 */

/**
 * Generate Leader Report within the group.
 *
 * Output is accomplished via a template tag, for easy insertion in group pages.
 *
 * @since   1.8.0
 *
 * @return  string The html for the leader report
 */
function nyscaa_poverty_report() {
	/*
	 * Is there a geoid set? We determine whether to show the report or the county
	 * selector based on this variable.
	 */
	$geoid = isset( $_GET['geoid'] ) ? $_GET['geoid'] : '';
	$plugin_url = plugins_url('', dirname(__FILE__));
  
	?>
	<div class="content-row clear">
		<?php if (! $geoid): 	?>
			<div id="nyscaa-report-selection" class="report-control-header clear">
				<h3 class="screamer">POVERTY DATA: Create a poverty report for your community</h3>
        <p>
        <a href="<?php echo $plugin_url?>/images/NYSCAA_PovertyReport_NY.pdf">
          <img src="<?php echo $plugin_url?>/images/ny-poverty.png" style="float: right; width: 150px; border: 1px solid #dedede; margin-left: 30px;" />
        </a>
          The New York State Community Action Association (NYSCAA) is pleased to present its online edition of the New York State Poverty Report. 
          Providing a statewide look at poverty, this tool is designed to be a comprehensive resource for New York’s Community Action Agencies, 
          community-based organizations, policy makers, advocates, community coalitions and the general public. 
          This report does not offer policy recommendations; rather, the intent is to serve as an informational source that adds value to the larger 
          dialogue on how to address poverty in communities across our state.
        </p>
        <div class="nyscaa-report-type">
          <p><b>New York State Poverty Report:</b></p>
          <ul>
            <li><a href="?geoid=04000US36">NY State Poverty Profile</a></li>
            <li>
              <a href="?geoid=01000US">US Poverty Profile</a>
            </li>
            <li>
              <a href="?geoid=county-poverty">A Comparative Look at County Poverty Levels</a>
            </li>
            <li><a href="?geoid=datakey">Data Key</a></li>
          </ul>
          <div id="report-wait-state" class="report-processing-msg">Loading your report, please wait...</div>
        </div>

        <div class="nyscaa-report-type">
          <p><b>Find A County Profile:</b></p>
          <select id="nyscaa-report-county-list">
					  <option value="" selected>--- Choose a County ---</option>
				  </select>
          <span id="nyscaa-report-wait-county" class="report-processing-msg">Loading your report, please wait...</span>
        </div>

        <div class="nyscaa-report-type">
          <p><b>Find A City Profile:</b></p>
         <select id="nyscaa-report-city-list">
					  <option value="" selected>--- Choose a City ---</option>
         </select>
				  <span id="nyscaa-report-wait-city" class="report-processing-msg">Loading your report, please wait...</span>
        </div>

			</div>
		<?php else: 
        // we have $geoid in the url parameter
        $can_save = current_user_can( 'bp_docs_associate_with_group', nyscaa_get_group_id() ) && ($geoid != "datakey");
    ?>
		<div id="nyscaa-report-action-top" class="nyscaa-report-action">
		<input type="button" class="button nyscaa-report-export" id="nyscaa-report-export-top" value="Export Report to PDF" />
		</div>
		<div id="nyscaa-report-content">
    <?php if ( $geoid == "datakey" ): ?>
      <div id="nyscaa-report-datakey">
        <div id="nyscaa-report-datakey-left">
          <p>
            <b>Maps:</b></p>
          <p>
            On each county profile page, the map of NYS is included, with the county highlighted in red. The inset map shows the
            county map with the county seat noted. On city profile pages, the map of NYS is again included, with the county
            highlighted in grey and the cities noted in red. Inset map is the county, with cities noted.
          </p>
          <p>
            <b>Population Data:</b></p>
          <p>The population is the official count of people from the US Census Bureau's (USCB) BO1003 report.</p>
          <p>The Population for Whom Status is Determined reflects the size of census samples and is provided in USCB report S1701.</p>
          <p>
            <b>Poverty Data:</b></p>
          <p>The poverty rate includes all individuals living in poverty. The numbers are then broken down in three other categories:
            children under 18, adults over 25 and adults over 65.</p>
          <p>The percentage of each group living in poverty is followed by the number of individuals that percentage represents.</p>
          <p>Families in Poverty Data is from USCB Report S1702 and indicates the percentage of all families with a female head of
            household and at least one child under 18 who is living in poverty.</p>
          <div class="nyscaa-report-datakey-note center-align">
            <p>
            ALL Census Reports cited are from the American Communities Survey FIVE year estimates (2011 - 2015). <br />
            Access these reports at: <a href="http://factfinder2.census.gov" target="_blank">factfinder2.census.gov</a>
            </p>
          </div>
        </div>
        <div id="nyscaa-report-datakey-right">
          <div id="nyscaa-report-datakey-1">
            <div id="nyscaa-report-title">
              <div id="nyscaa-report-title1">Data Key</div>
              <div id="nyscaa-report-title2" class="nyscaa-report-datatitle">Race<br />&amp; Poverty<br/>Data</div>
            </div>
            <div id="nyscaa-report-datenote">
              <div id="nyscaa-report-censusbox">
                Please see <a href="http://census.gov" target="_blank">census.gov</a> for more specific definitions for any of the data sourced from USCB reports.
              </div>
              <div id="nyscaa-report-racenote">
                <p>
                Data on race is based on self-identification. There are a minimum of five categories from which to select, including
                White and Black/African American. People are able to self-identify as more than one race. People who
                identify their origin as Hispanic, Latino or Spanish may be of any race. The data is from USCB Report S1701.
              </p>
              </div>
            </div>
          </div>
          <div id="nyscaa-report-datakey-2">
            <div class="nyscaa-report-datatitle">
              <img src="<?php echo $plugin_url ?>/images/education.png" style="height:80px; display:inline-block; vertical-align:middle;" />
              <span id="nyscaa-report-edupov">Education  &amp; Poverty Data</span>
              <img src="<?php echo $plugin_url ?>/images/university.png" style="height: 50px; vertical-align:bottom" />
            </div>
            <p>
              The top numbers provided, next to the dark blue squares, indicate the total for each category, as a percentage of the entire population 
              and number of individuals. The number below, next to the lighter square, shows the percentage of all people in that category living
              in poverty and total number of individuals that indicates. 
              This data is from USCB Report S1701.
            </p>
          </div>
          <div id="nyscaa-report-datakey-3">
            <div class="nyscaa-report-datatitle">
              <img src="<?php echo $plugin_url ?>/images/income.png" height="65px" style="float: right" />              
              Income
              <div class="nyscaa-report-datatitle2">&amp; Poverty Data</div>
            </div>
            <p>
              <b>Median Income - </b>Total income is the sum of the amounts reported for wage/salary income, self
              employment income, interest, dividends, rental income, royalty income, income from estates or trusts, Social Security or 
              Railroad Retirement income, Supplementary Social Security, public assistance or welfare payments, 
              retirement/survivor/disability pensions and all other income. 
              The data is from USCB Report S1501.
            </p>
          </div>
          <div id="nyscaa-report-datakey-4">
            <div id="nyscaa-report-wagekey">
              <p>
                <b>Living Wage -</b>
                The wage listed is the wage an individual would need to earn as the sole provider for a household consisting
                of themselves and one child based on the typical expenses in that county or city. This wage is a minimum estimate of the
                cost of living for a low wage family. Data from Massachusetts Institute of Technology Living Wage Calculator
                (<a href="http://livingwage.mit.edu" target="_blank">livingwage.mit.edu</a>), released in June 2016.
              </p>
              <p>
                <b>Hourly Wage -</b>
                The hourly rate listed is that which one person would need to earn working year round, 40 hours per
                week in order to afford a two bedroom apartment at the fair market rate (FMR) for that county, assuming 30% of income
                is spent on housing. Data is provided by the National Low Income Housing Coalition's report: 
                Out of Reach 2016 (<a href="http://www.nlihc.org" target="_blank">www.nlihc.org</a>).
              </p>
            </div>
            <div id="nyscaa-report-datakey-41">
              <div class="nyscaa-report-datatitle">Health &amp; Poverty
              <div class="nyscaa-report-datatitle2">Insurance</div></div>
              <p>
                Based on self reporting this includes people who have: insurance from a current/former
                employer, insurance purchased directly from an insurance company, Medicare, Medicaid, Medical
                Assistance, any government assistance plan for people with low income or disability, TRICARE
                or military health care, VA, Indian Health Service or any other type of health insurance or
                health coverage plan. Data from USCB Report S2701.
              </p>

              <img src="<?php echo $plugin_url ?>/images/school-lunch.png" height="55px" style="float: right" />              
              <div class="nyscaa-report-datatitle">Free/Reduced
              <div class="nyscaa-report-datatitle2">Lunch Program</div>
              </div>

              <p>
                Of students who attend public schools where National School Lunch Program (NSLP) is
                offered, the percentage of enrollment that is eligible for free or reduced lunches. This does
                not represent those attending charter schools or schools that do not administer NSLP. Data
                from New York State Education Department (NYSED), reporting students eligible 
                for NSLP during January 2016.
              </p>
            </div>
          </div>
          <div id="nyscaa-report-datakey-5">
            <p>
            <span class="nyscaa-report-datakey-note">Female Head of Household</span> 
            Of all households with a female head of household and children
            under 18, this is the percentage living in poverty. The data is from USCB Report S1702.</p>
          </div>
        </div>
      </div>
    <?php elseif ( $geoid == "county-poverty"): ?>
      <div id="nyscaa-report-county-poverty-list">
        <div id="nyscaa-report-county-poverty-title">A Comparative Look at County Poverty Levels</div>

        <?php 
          // get poverty data for all counties
          $poverty_data = nyscaa_report_api('6240?area_ids=04000US36&area_type=state&data_county=1');
          $county_data = $poverty_data->data->county_list;

          // just get the total number(index=6) and percent (index=7) of poverty for each county
          $county_poverty = array();
          for($i = 0; $i< count($county_data); $i++){
            $county_poverty[$i] = array();
            $county_poverty[$i]["name"] = str_replace(" County, NY", "", $county_data[$i]->values[0]);
            $county_poverty[$i]["total"] = floatval(str_replace(",", "", $county_data[$i]->values[6]));
            $county_poverty[$i]["rate"] = floatval($county_data[$i]->values[7]);
            $county_poverty[$i]["rank_total"] = 0;
            $county_poverty[$i]["rank_rate"] = 0;
          }
          
          // get the top 10 counties with the highest number of people in poverty         
          sksort($county_poverty, "total");
          for($x = 0; $x < count($county_poverty); $x++){
            $county_poverty[$x]["rank_total"] = $x + 1; 
          }
          $county_poverty_top10 = array();
          for($x = 0; $x < 9; $x++){
             array_push($county_poverty_top10, $county_poverty[$x]["name"]);
          }
          array_push($county_poverty_top10, ' and ' . $county_poverty[$x]["name"]);

          // get the top 10 counties with the highest percent of people in poverty 
          sksort($county_poverty, "rate");
          for($x = 0; $x < count($county_poverty); $x++){
            $county_poverty[$x]["rank_rate"] = $x + 1; 
          }
          $county_poverty_rate10 = array();
          for($x = 0; $x < 9; $x++){
            array_push($county_poverty_rate10, $county_poverty[$x]["name"]);
          }
          array_push($county_poverty_rate10, ' and ' . $county_poverty[$x]["name"]);
          
          // sort the poverty data array by county name
          sksort($county_poverty, "name", true);
        ?>
        The ten New York State counties with the greatest number of people living in poverty, from highest to
        lowest, are: <?php echo implode(', ', $county_poverty_top10) ?>.
        The ten counties with the highest percentage of the population living in poverty, from highest to lowest,
        are: <?php echo implode(', ', $county_poverty_rate10) ?>.

        <table>
          <thead style="display: table-header-group">
          <tr>
            <th>County</th>
            <th>Number in Poverty</th>
            <th>Rank by Number</th>
            <th>Percent in Poverty</th>
            <th>Rank by Percent</th>
          </tr>
          </thead>
          <tbody>
          <?php

          foreach($county_poverty as $county){
            echo '<tr><td>' . $county["name"] . '</td><td>' . number_format($county["total"]) . '</td><td>' . $county["rank_total"] . 
            '</td><td>'. $county["rate"] . '%</td><td>' . $county["rank_rate"] . '</td></tr>';
          }
          ?>
          </tbody>
        </table>

        This data from the US Census Bureau's American Communities Survey (ACS) report S1701 - Poverty Status in the Past
        12 Months, 2011 - 2015 five year estimates. (factfinder2.census.gov)
      </div>
     <?php 
     else: 
      // generate report
      $sum_level = "county";
      switch( substr($geoid, 0, 3) ){
        case "040":
          $sum_level = "state";
          break;
         case "160":
          $sum_level = "city";
          break;
         case "010":
          $sum_level = "us";
          break;
      }
      
      // get location information - NameLSAD|City|CountyMap|CityMap|Address1|Address2|Phone|NYSCAA|Logo|Url|FIPS
      $location_data = nyscaa_report_get_summary($geoid, $sum_level, '0');
      
      // get logo
      $location_logo = ($location_data[8] == "") ? "": $plugin_url . "/images/logos/" . $location_data[8];
      
      // get map - use county map if county-level report or city map not available
      $location_map = ($sum_level != "city" || $location_data[3] == "")? $location_data[2]: $location_data[3];
      $location_map = ($location_map == "")? $location_logo: $plugin_url . "/images/maps/" . $location_map;
      
      // get general poverty data
      $poverty_data = nyscaa_report_get_summary($geoid, $sum_level, '6240');
     ?>
      <div id="nyscaa-report-infograph">
        <div id="nyscaa-report-top">
          New York State Community Action Association 
          <img src="<?php echo $plugin_url?>/images/dark-blue-dot.png" style="width: 7px; padding: 0 10px 2px 10px; vertical-align:middle;" />
          <a href="http://www.nyscommunityaction.org" target="_blank">www.nyscommunityaction.org</a> 
        </div>
        <div id="nyscaa-report-content-left">
          <div id="nyscaa-report-map">
                <img src="<?php echo $location_map?>" />
          </div>
          <?php if ($location_logo != "" && $sum_level != "state" && $sum_level != "us"): ?>
            <div id="nyscaa-report-logo">
              <img src="<?php echo $location_logo ?>" style="max-width: 170px; max-height:70px" />
            </div>
          <?php endif; ?>
          <div id="nyscaa-report-address">
            <?php echo $location_data[4] ?>
            <br />
            <?php echo $location_data[5] ?>
            <br />
            <?php echo $location_data[6] ?>
          </div>
          <p></p>
          <div class="center-align font-bold font-mid">
            <p>
              <?php echo ($sum_level == "us")? "United States": ucfirst($sum_level)?> Population:<br />
              <?php echo $poverty_data[1]?>
            </p>
          </div>
          <div id="nyscaa-report-popnote">
            Population for whom poverty status is determined:
          </div>
          <div id="nyscaa-report-pop-age">
            Overall<br/>
            <b>
              <?php echo $poverty_data[2]?>
            </b><br/>
            Population Under 18<br/>
            <b>
              <?php echo $poverty_data[3]?>
            </b><br/>
            Population 25 &amp; Over<br/>
            <b>
              <?php echo $poverty_data[4]?>
            </b><br/>
            Population over 65 <br/>
            <b>
              <?php echo $poverty_data[5]?>
            </b>
          </div>
          <div id="nyscaa-report-povrate">
            <?php echo nyscaa_report_format_pct($poverty_data[7])?>
          </div>
          <div id="nyscaa-report-geoglevel">
            <?php echo ($sum_level == "us")? "National": strtoupper($sum_level)?>
          </div>
          <div id="nyscaa-report-poverty">
            POVERTY
          </div>
          <div id="nyscaa-report-poverty-rate">
            RATE
          </div>
          <div id="nyscaa-report-living">
            <p></p>
            <div class="dark-blue-text font-mid">Living In Poverty</div>
            <p>Individuals</p>
            <div class="nyscaa-report-v1">
              <?php echo nyscaa_report_format_pct($poverty_data[7])?>
            </div>
            <div class="nyscaa-report-v2">
              <?php echo $poverty_data[6]?>
            </div>
            <p>Children (Under 18)</p>
            <div class="nyscaa-report-v1">
              <?php echo nyscaa_report_format_pct($poverty_data[9])?>
            </div>
            <div class="nyscaa-report-v2">
              <?php echo $poverty_data[8]?>
            </div>
            <p>Adults 25+</p>
            <div class="nyscaa-report-v1">
              <?php echo nyscaa_report_format_pct($poverty_data[11])?>
            </div>
            <div class="nyscaa-report-v2">
              <?php echo $poverty_data[10]?>
            </div>
            <p>Senior Citizens 65+</p>
            <div class="nyscaa-report-v1">
              <?php echo nyscaa_report_format_pct($poverty_data[13])?>
            </div>
            <div class="nyscaa-report-v2">
              <?php echo $poverty_data[12]?>
            </div>
          </div>
        </div>
        
        <div id="nyscaa-report-content-right">

          <div style="font-weight:700; font-size:20pt;">
            <?php echo ($sum_level == "city")? $location_data[1]: $location_data[0] ?>
          </div>

          <div style="font-weight:700; font-size:14pt; line-height: 1.5;">
            <?php echo $location_data[7] ?>
          </div>
          <div style="font-weight:500; font-size: 12pt; margin-bottom: 10px;">
            <a href="http://<?php echo $location_data[9]?>" target="_blank"><?php echo $location_data[9]?></a>
          </div>
          
          <div id="nyscaa-report-content-race" class="section-spacing">
            <div class="third-block">
              <div class="nyscaa-report-content-title">Race &amp;
              <div class="nyscaa-report-content-title2">Poverty</div>
              </div>
            </div>
            <div class="third-block">
              <div class="nyscaa-report-content-name">White</div>
              <div class="nyscaa-report-content-name">African American</div>
              <div class="nyscaa-report-content-name">Hispanic/Latino</div>
            </div>
            <div class="third-block">
              <div class="nyscaa-report-content-value">
                <?php echo nyscaa_report_format_pct( $poverty_data[15] ) . " (" . $poverty_data[14] . ")"?>
              </div>
              <div class="nyscaa-report-content-value">
                <?php echo nyscaa_report_format_pct( $poverty_data[17] ) . " (" . $poverty_data[16] . ")"?>
              </div>
              <div class="nyscaa-report-content-value">
                <?php echo nyscaa_report_format_pct( $poverty_data[19] ) . " (" . $poverty_data[18] . ")"?>
              </div>
            </div>
          </div>
          
          <div id="nyscaa-report-content-edu" class="section-spacing">
            <?php  $education_data = nyscaa_report_get_summary($geoid, $sum_level, '6242'); ?>
            <table>
              <tr>
                <td style="text-align: left; vertical-align: bottom;">
                  <img src="<?php echo $plugin_url ?>/images/education.png" style="height:80px;"/>
                  </td>
                <td style="width: 78%" class="center-align">
                  <span class="nyscaa-report-content-title">Education </span>
                  <span class="nyscaa-report-content-title2">&amp; Poverty</span>
                  <div style="background: #ddd; font-weight: 700; color: #000; padding: 3px 0;">
                    Adult Population 25+: <?php echo $education_data[1] ?>
                  </div>
                </td>
                <td style="vertical-align: bottom; text-align: right;">
                  <img src="<?php echo $plugin_url ?>/images/university.png" style="height:50px;"/>
                </td>
              </tr>
            </table>

            <div class="clear-float nyscaa-report-content-title3 center-align">Educational Attainment</div>
              <div class="content-row">
              <div class="quarter">
                <div class="nyscaa-report-content-name center-align">No Degree</div>
                <span class="nyscaa-report-legend-dark">&nbsp;</span> 
                <span class="nyscaa-report-content-value-sm">
                  <?php echo nyscaa_report_format_pct( $education_data[3] ) . " (" . $education_data[2] . ")"?>
                </span>
                <div class="nyscaa-report-content-name padding-left10">Total*</div>
                <span class="nyscaa-report-legend-light">&nbsp;</span>
                <span class="nyscaa-report-content-value-sm">
                  <?php echo nyscaa_report_format_pct( $education_data[11] ) . " (" . $education_data[10] . ")"?>
                </span>
                <div class="nyscaa-report-content-name-light padding-left10">Living in Poverty</div>               
              </div>
              <div class="quarter">
                <div class="nyscaa-report-content-name center-align">High School</div>
                <span class="nyscaa-report-legend-dark">&nbsp;</span>
                <span class="nyscaa-report-content-value-sm">
                  <?php echo nyscaa_report_format_pct( $education_data[5] ) . " (" . $education_data[4] . ")"?>
                </span>
                <div class="nyscaa-report-content-name padding-left10">Total*</div>
                <span class="nyscaa-report-legend-light">&nbsp;</span>
                <span class="nyscaa-report-content-value-sm">
                  <?php echo nyscaa_report_format_pct( $education_data[13] ) . " (" . $education_data[12] . ")"?>
                </span>
                <div class="nyscaa-report-content-name-light padding-left10">Living in Poverty</div>
              </div>
              <div class="quarter">
                <div class="nyscaa-report-content-name center-align">Associate</div>
                <span class="nyscaa-report-legend-dark">&nbsp;</span>
                <span class="nyscaa-report-content-value-sm">
                  <?php echo nyscaa_report_format_pct( $education_data[7] ) . " (" . $education_data[6] . ")"?>
                </span>
                <div class="nyscaa-report-content-name padding-left10">Total*</div>
                <span class="nyscaa-report-legend-light">&nbsp;</span>
                <span class="nyscaa-report-content-value-sm">
                  <?php echo nyscaa_report_format_pct( $education_data[15] ) . " (" . $education_data[14] . ")"?>
                </span>
                <div class="nyscaa-report-content-name-light padding-left10">Living in Poverty</div>
              </div>
              <div class="quarter">
                <div class="nyscaa-report-content-name center-align">Bachelors or Higher</div>
                <span class="nyscaa-report-legend-dark">&nbsp;</span>
                <span class="nyscaa-report-content-value-sm">
                  <?php echo nyscaa_report_format_pct( $education_data[9] ) . " (" . $education_data[8] . ")"?>
                </span>
                <div class="nyscaa-report-content-name padding-left10">Total*</div>
                <span class="nyscaa-report-legend-light">&nbsp;</span>
                <span class="nyscaa-report-content-value-sm">
                  <?php echo nyscaa_report_format_pct( $education_data[17] ) . " (" . $education_data[16] . ")"?>
                </span>
                <div class="nyscaa-report-content-name-light padding-left10">Living in Poverty</div>
              </div>
              </div>
              <div class="clear-float center-align" style="font-size: 10px;">
                *DUE TO ROUNDING, PERCENTAGES MAY NOT ADD UP PRECISELY TO 100%.
              </div>
          </div>

          <div id="nyscaa-report-content-emp" class="section-spacing">
            <div id="emp-part1">
              <img src="<?php echo $plugin_url ?>/images/income.png" style="height: 80px;"/>
                <?php 
                if ( $sum_level != "city" && $sum_level != "us"): 
                  $living_wage =  nyscaa_report_get_summary($geoid, $sum_level, '6085');                  
             ?>
              <div class="dark-blue-text center-align" style="padding: 5px 15px 0 5px">
                Living Wage for <br />1  Adult, 1 Child Household
              </div>
              <div class="center-align">
                  <b><?php echo $living_wage[2] ?></b>
               </div>
             <?php endif; ?>               
            </div>
            <div id="emp-part2"  class="gray-border">
              <div id="emp-part2-a">
                <div class="nyscaa-report-content-title">Employment</div>
                <div class="nyscaa-report-content-title2">&amp; Poverty</div>
                
                <?php if ($sum_level != "city"): ?>
                  <div class="dark-blue-text center-align" style="margin-top: 20px" >
                    Hourly Wage for <br />FMR, 2BR Apartment
                  </div>
                  <div class="center-align">
                    <b><?php 
                    if ($sum_level == "us"){
                      echo "$20.30";
                    }else{
                      $hourly_wage = nyscaa_report_get_summary($geoid, $sum_level, '6091');                      
                      echo $hourly_wage[4];
                    }
                    ?></b>
                  </div>
              <?php 
                endif; 

                // get median income data
                $median_income = nyscaa_report_get_summary($geoid, $sum_level, '6241');
              ?>                
              </div>
              <div id="emp-part2-b" class="center-align">
                <div class="dark-blue-text">Median Income</div>
                <div class="center-align">
                  <?php echo nyscaa_report_check_nodata($median_income[1])?>
                </div>

                <div class="dark-blue-text" style="margin-top: 10px;">Median Income <br />w/High School Diploma</div>
                <div class="center-align">
                  <?php echo nyscaa_report_check_nodata($median_income[2])?>
                </div>                
              </div>
            </div>      
          </div>

          <div id="nyscaa-report-content-health" class="section-spacing">
            <?php $health_data = nyscaa_report_get_summary($geoid, $sum_level, '6243'); ?>
            <div id="health-part1">
              <div class="nyscaa-report-content-title">Health</div>
              <div class="nyscaa-report-content-title2">&amp; Poverty</div>
            </div>
            <div id="health-part2" class="gray-border">
              <div id="health-part2-a" class="center-align">
                  <div class="nyscaa-report-content-title3">No Health Insurance</div>
                <div class="dark-blue-text">Employed</div>
                <b>
                  <?php echo nyscaa_report_format_pct( $health_data[1] )?></b>
                <div class="dark-blue-text">Unemployed</div>
                <b>
                  <?php echo nyscaa_report_format_pct( $health_data[2] )?></b>               
              </div>
              <div id="health-part2-b" class="center-align">
                <?php if ($sum_level != "city" ): ?>
                <div class="nyscaa-report-content-title3">Free/Reduced Lunch Program</div>
                <p></p>
                <?php if ($sum_level == "us"): ?>
                <img src="<?php echo $plugin_url?>/images/school-lunch.png" style="height: 50px; float: left; margin-right: 10px;"/>
                <b>
                  22 million children received free or reduced lunch each day in 2016</b>
                <?php else: ?>
                  <img src="<?php echo $plugin_url?>/images/school-lunch.png" style="height: 50px"/>
                  <span style="font-size: 50pt; line-height: 1; font-family: Bodoni MT,Garamond,Times New Roman,serif;" class="dark-blue-text">
                    <?php                   
                    $fr_lunch_data = nyscaa_report_get_summary($geoid, $sum_level, '6144'); 
                    echo nyscaa_report_format_pct($fr_lunch_data[3], 0);
                    ?>
                  </span>
                  <?php endif; ?> 
                <?php endif; ?>
              </div>
            </div>
          </div>

          <div id="nyscaa-report-content-gender" class="section-spacing">
            <?php $earning_data = nyscaa_report_get_summary($geoid, $sum_level, '6245'); ?>
            <div id="gender-part1" class="gray-border">
              <div id="gender-part1-a">
                <div class="nyscaa-report-content-title">Gender</div>
                <div class="nyscaa-report-content-title2">&amp; Poverty</div>
                <div class="nyscaa-report-content-title3">High School Diploma Only</div>
              </div>

              <div id="gender-part1-b" style="padding-top: 10px;" class="center-align">
                <img src="<?php echo $plugin_url?>/images/male.png" style="height: 70px"/>
                  <div class="dark-blue-text">Median<br />Earnings</div>
                  <?php echo nyscaa_report_check_nodata($earning_data[1])?>
              </div>

              <div id="gender-part1-b" style="padding-top: 10px;" class="center-align">
                <img src="<?php echo $plugin_url?>/images/female.png" style="height: 70px"/>
                  <div class="dark-blue-text">Median<br />Earnings</div>
                  <?php echo nyscaa_report_check_nodata($earning_data[2])?>
              </div>
            </div>

            <div id="gender-part2" style="background-color: #ddd; padding: 10px;" class="center-align">
              <div class="dark-blue-text">Of Those Families with</div>
              <div class="light-blue-text">Female Heads of Household</div>
              <div class="dark-blue-text">and Children Present</div>
              <div class="dark-blue-text" style="font-size: 18pt; margin-top: 10px;">
                <?php 
                  $female_pov = nyscaa_report_get_summary($geoid, $sum_level, '6244'); 
                  echo nyscaa_report_format_pct( $female_pov[2] );
                ?>
                <br />
                Live in Poverty
              </div>
            </div>

            <div id="nyscaa-report-footer-povrate" class="clear-float center-align light-blue-text">
              US Poverty Rate: 15.5%
              <img src="<?php echo $plugin_url?>/images/light-blue-dot.png" />
                NYS Poverty Rate: 15.7%
                <?php
                  // if report is for a city, show county poverty rate
                  if ($sum_level == "city") {
                    echo "<img src='" . $plugin_url . "/images/light-blue-dot.png' />";
                    
                    // get county poverty data
                    $poverty_data = nyscaa_report_get_summary('05000US' . preg_replace( '/[^0-9.]/', '', $location_data[10]), 'county', '6240');
                    echo $location_data[0] . " Poverty Rate: " . nyscaa_report_format_pct( $poverty_data[7] );
                  }
                ?>
            </div>
          </div>
        </div>        
      </div>
     <?php endif; ?>
		</div>
    <div class="nyscaa-report-action"></div>
    <div id="nyscaa-report-action-bottom" class="nyscaa-report-action">
      <input type="button" class="button nyscaa-report-export" id="nyscaa-report-export-bottom" value="Export Report to PDF" />

      <input type="hidden" id="nyscaa-report-geoid" value="<?php echo $geoid ?>" />
      <input type="hidden" id="nyscaa-report-area" value="<?php echo $report_area ?>" />
      <input type="hidden" id="nyscaa-report-wpnonce" value="<?php echo wp_create_nonce( 'save-nyscaa-poverty-report-' . bp_loggedin_user_id() ) ?>" />
    </div>
    <?php endif; ?>

	</div>
  <?php
}

function nyscaa_report_console_log( $data ){
	echo '<script>';
	echo 'console.log(1, '. json_encode( $data ) .')';
	echo '</script>';
}

// get json object from API service
function nyscaa_report_api($api_params){
  	$api_url = 'https://services.communitycommons.org/api-report/v1/indicator/NYSCAA-Poverty/';
	  $api_url .= $api_params;
    //nyscaa_report_console_log($api_url);
	  $result = file_get_contents($api_url);
    $json =  json_decode($result);
    return $json;
}

function nyscaa_report_get_summary($area_ids, $area_type = 'county', $data_id='0'){
	$param = $data_id . '?area_ids=' . $area_ids . '&area_type=' . $area_type . $more;
  $json = nyscaa_report_api($param);
  $values = $json->data->summary->values;
  //nyscaa_report_console_log($values);
	return $values;
}

// write the percentage in single digit format
function nyscaa_report_format_pct($value, $digit = 1){
	if ($value == 0) {
		return "0%";
	}else{
		return number_format($value, $digit) . "%";
	}
}

// handle 'no data'
function nyscaa_report_check_nodata($value){
  return ( strpos($value, 'no data') !== false )? 'data not available': '<b>$' . $value . '</b>'; 
}

// sort an array by the key of it sub-array
function sksort(&$array, $subkey="name", $sort_ascending=false) {
    if (count($array)){
      $temp_array[key($array)] = array_shift($array);
    }

    foreach($array as $key => $val){
        $offset = 0;
        $found = false;
        foreach($temp_array as $tmp_key => $tmp_val)
        {
            if(!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey]))
            {
                $temp_array = array_merge(    (array)array_slice($temp_array,0,$offset),
                                            array($key => $val),
                                            array_slice($temp_array,$offset)
                                          );
                $found = true;
            }
            $offset++;
        }
        if(!$found) $temp_array = array_merge($temp_array, array($key => $val));
    }

    if ($sort_ascending) {
      $array = array_reverse($temp_array);
    } else {
      $array = $temp_array;
    }
}

