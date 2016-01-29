<?php
/**
 * Calendar Board Field for Kirby CMS
 *
 * @author    Marco Oliva - team@moloc.net
 * @version   0.2
 *
 */
 
require('calendar.php');

class CalendarboardField extends BaseField {
  
  static public $assets = array(
      'js' => array(
          'calendarboard.js'
      ),
      'css' => array(
          'calendarboard.css'
      )
  );
  
  public function routes() {
    return array(
      array(
        'pattern' => 'get-month-board/(:num)/(:num)',
        'method'  => 'get',
        'action' => function($month, $year)
        {  
          date_default_timezone_set('UTC');
          
          $l = panel()->language();
          setlocale(LC_ALL, $l . '_' . str::upper($l)); 
          
          $cal= new calendar();
          
          $route_url = purl($this->model, 'field/' . $this->name . '/calendarboard/get-day/');
          
          $currentMonth = $cal->month($year, $month);
          $prevMonth = $currentMonth->prev();
          $nextMonth = $currentMonth->next();
        
          /* Calendar board header navigation */
          $h = '<div class="cf">';
          $h .= '<span class="month__prev" onclick="builtCalendar(\'' . $this->name . '\',' . $prevMonth->int() . ',' . $prevMonth->year()->int() . ')">&#139;</span>';
          $h .= '<div class="calendar__title">' . $currentMonth->name() . ' ' . $currentMonth->year() . '</div>';
          $h .= '<span class="month__next" onclick="builtCalendar(\'' . $this->name . '\',' . $nextMonth->int() . ',' . $nextMonth->year()->int() . ')">&#155;</span>';
          $h .= '</div>'; 
          
            /* Calendar board week day*/
            foreach($currentMonth->weeks()->first()->days() as $weekDay){
              $h .= '<div class="weekday">' . $weekDay->shortname() . '</div>';
            }
            
            /* Calendar board days */
            foreach($currentMonth->weeks(6) as $week){
              
              foreach($week->days() as $day){
              
                $class = '';
                $go = 'href="' . $route_url . $day . '"';
                
                if ($day->month() != $currentMonth){
                  $class='is-sibling';
                  $go = '';
                }
                
                if ($day->isToday()){
                  $class='is-today';
                }
                
                $h .= '<a class="day ' . $class . '" '  . $go . '>';
                $h .= '<img src="data:image/gif;base64,R0lGODlhkAGQAYAAAP///wAAACH/C1hNUCBEYXRhWE1QPD94cGFja2V0IGJlZ2luPSLvu78iIGlkPSJXNU0wTXBDZWhpSHpyZVN6TlRjemtjOWQiPz4gPHg6eG1wbWV0YSB4bWxuczp4PSJhZG9iZTpuczptZXRhLyIgeDp4bXB0az0iQWRvYmUgWE1QIENvcmUgNS42LWMwMTQgNzkuMTU2Nzk3LCAyMDE0LzA4LzIwLTA5OjUzOjAyICAgICAgICAiPiA8cmRmOlJERiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiPiA8cmRmOkRlc2NyaXB0aW9uIHJkZjphYm91dD0iIiB4bWxuczp4bXA9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC8iIHhtbG5zOnhtcE1NPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvbW0vIiB4bWxuczpzdFJlZj0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL3NUeXBlL1Jlc291cmNlUmVmIyIgeG1wOkNyZWF0b3JUb29sPSJBZG9iZSBQaG90b3Nob3AgQ0MgMjAxNCAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6N0M4Q0I3NjhEOUQxMTFFNEJBNEFBQ0Q3NkE3QjNBMjYiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6N0M4Q0I3NjlEOUQxMTFFNEJBNEFBQ0Q3NkE3QjNBMjYiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo3QzhDQjc2NkQ5RDExMUU0QkE0QUFDRDc2QTdCM0EyNiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo3QzhDQjc2N0Q5RDExMUU0QkE0QUFDRDc2QTdCM0EyNiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PgH//v38+/r5+Pf29fTz8vHw7+7t7Ovq6ejn5uXk4+Lh4N/e3dzb2tnY19bV1NPS0dDPzs3My8rJyMfGxcTDwsHAv769vLu6ubi3trW0s7KxsK+urayrqqmop6alpKOioaCfnp2cm5qZmJeWlZSTkpGQj46NjIuKiYiHhoWEg4KBgH9+fXx7enl4d3Z1dHNycXBvbm1sa2ppaGdmZWRjYmFgX15dXFtaWVhXVlVUU1JRUE9OTUxLSklIR0ZFRENCQUA/Pj08Ozo5ODc2NTQzMjEwLy4tLCsqKSgnJiUkIyIhIB8eHRwbGhkYFxYVFBMSERAPDg0MCwoJCAcGBQQDAgEAACH5BAEAAAAALAAAAACQAZABAAL/hI+py+0Po5y02ouz3rz7D4biSJbmiabqyrbuC8fyTNf2jef6zvf+DwwKh8Si8YhMKpfMpvMJjUqn1Kr1is1qt9yu9wsOi8fksvmMTqvX7Lb7DY/L5/S6/Y7P6/f8vv8PGCg4SFhoeIiYqLjI2Oj4CBkpOUlZaXmJmam5ydnp+QkaKjpKWmp6ipqqusra6voKGys7S1tre4ubq7vL2+v7CxwsPExcbHyMnKy8zNzs/AwdLT1NXW19jZ2tvc3d7f0NHi4+Tl5ufo6err7O3u7+Dh8vP09fb3+Pn6+/z9/v/w8woMCBBAsaPIgwocKFDBs6fAgxosSJFCtavIgxo8aN/xw7evwIMqTIkSRLmjyJMqXKlSxbunwJM6bMmTRr2ryJM6fOnTx7+vwJNKjQoUSLGj2KNKnSpUybOn0KNarUqVSrWr2KNavWrVy7ev0KNqzYsWTLmj2LNq3atWzbun0LN67cuXTr2r2LN6/evXz7+v0LOLDgwYQLGz6MOLHixYwbO34MObLkyZQrW76MObPmzZw7e/4MOrTo0aRLmz6NOrXq1axbu34NO7bs2bRr276NO7fu3bx7+/4NPLjw4cSLGz+OPLny5cybO38OPbr06dSrW7+OPbv27dy7e/8OPrz48eTLmz+PPr369ezbu38PP778+fTr27+PP7/+/fz7+03/D2CAAg5IYIEGHohgggouyGCDDj4IYYQSTkhhhRZeiGGGGm7IYYcefghiiCKOSGKJJp6IYooqrshiiy6+CGOMMs5IY4023ohjjm0UAAA7" />';
                $h .= '<p>' . $day->int() . '</p>';
                $h .= '<div class="events">';
                  
                  /* Events in the day */
                  if ($day->month() == $currentMonth){
                    if(page($this->model . '/day-' . $day)){
                    
                      $events = page($this->model . '/day-' . $day)->events()->toStructure();
                      
                      foreach($events as $event){
                        $h .= '<div class="dot"></div>';
                      } 
                    }
                  }                  
                
                $h .= '</div>';
                $h .= '</a>';
              }
              
            }         
          
          return $h;
        }
      ),
      array(
        'pattern' => 'get-day/(:any)',
        'method' => 'get',
        'action' => function($day)
        {
          
          $date = str::split($day, '-');
          $date_friendly = $date[2] . '-' . $date[1] . '-' . $date[0];
          
          // If day doesn't exists, create it
          if(!site()->find($this->model . '/day-' . $day)){       
            page($this->model)->children()->create('day-' . $day, 'calendar-board-day', array(
              'title' => $date_friendly
            ));
          }          
          
          // Go to day edit page
          go(purl($this->model, 'day-' . $day . '/edit/'));
          
        }
      )
    );
  }  

  public function __construct() {
    $this->type   = 'calendarboard';
    $this->label  = l::get('fields.calendarboard.label', 'Calendar Board');
  }
    
  public function content() {
    $calendar = brick('div');
    $calendar->addClass('calendarboard');
    $calendar->data('field-name', $this->name);
    
    // Call createCalendar() in calendarboard.js
    $calendar->data("field", "createCalendar"); 

    return $calendar;

  }
}
