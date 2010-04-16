<?php
//
// Definition of eZEnhancedDebug class
//
// Created on: <25-Jun-2007 21:29:54 jr>
//
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.9.2
// BUILD VERSION: 18839
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//

include_once( 'lib/ezutils/classes/ezuri.php' );
include_once(  'kernel/common/template.php' );

class eZEnhancedDebug
{
    private function eZEnhancedDebug()
    {
        $GLOBALS["eZEnhancedDebug"] = $this;
    }

    public static function instance()
    {
        if( !isset( $GLOBALS['eZEnhancedDebug'] ) || !( $GLOBALS['eZEnhancedDebug'] instanceof eZEnhancedDebug ) )
        {
            $GLOBALS['eZEnhancedDebug'] = new eZEnhancedDebug();
        }

        return $GLOBALS['eZEnhancedDebug'];
    }

    public function startTimer()
    {
        $this->StartTimer = $this->microTime();
    }

    public function stopTimer()
    {
        $this->StopTimer = $this->microTime();
    }

    public function timeDifference()
    {
        $timeDifference = ( $this->StopTimer - $this->StartTimer );
        $timeDifference = number_format( $timeDifference, $this->TimingAccuracy );

        $this->TotalTime += $timeDifference;

        return $timeDifference;
    }

    private function microTime()
    {
        list( $usec, $sec ) = explode( " ", microtime() );
        return ( (float)$usec + (float)$sec );
    }

    public function appendToExecutionStack( $execution = array() )
    {
        // append time to time stack anyway
        $this->TimeStack[] = $execution['time'];

        // enable / disable operator filtering
        if( !isset( $this->OperatorFilteringIsEnabled ) )
        {
            $ini = eZINI::instance( 'ezenhanceddebug.ini' );

            $this->OperatorFilteringIsEnabled = true;

            if( $ini->variable( 'FilterSettings', 'UseOperatorFilters' ) == 'disabled')
            {
                $this->OperatorFilteringIsEnabled = false;
            }

            if( empty( $this->OperatorFilters ) )
            {
                $this->OperatorFilters = $ini->variable( 'FilterSettings', 'OperatorFilters' );
            }
        }

        if( $this->OperatorFilteringIsEnabled )
        {
            if( in_array( $execution['operator'] , $this->OperatorFilters ) )
            {
                return;
            }
        }

        if( !isset( $execution['iterations'] ) )
        {
            $execution['iterations'] = 0;
        }

        // define fetchedrows for all template functions/operators
        $execution['fetchedrows'] = $this->FetchedRows;

        // safety purpose only
        if( $execution['operator'] == 'fetch' )
        {
            $this->FetchedRows = 0;
        }

        $this->ExecutionStack[] = $execution;
    }

    private function getMedianTime()
    {
        // working on a copy
        $executionTimeSorted = $this->TimeStack;

        sort( $executionTimeSorted  );

        // median point : ( n + 1 ) / 2
        // beware : count returns one element more, we have our '+1'
        $count = count( $executionTimeSorted );

        if ( $count > 0 )
        {
            $medianPoint = $count / 2;

            return $executionTimeSorted[$medianPoint];
        }

        return 0;
    }

    private function getAverageTime()
    {
        $timeCounts = count( $this->TimeStack ) - 1;

        if( $timeCounts > 0 )
        {
            $averageTime = number_format( $this->TotalTime / $timeCounts, $this->TimingAccuracy ) ;

            return $averageTime;
        }

        return 0;
    }

    private function getSlowestFetch()
    {
        $executionStackTimeSorted = $this->sortExecutionStackByTime( 'DESC' );
        $fetchCode = '';

        foreach( $executionStackTimeSorted as $execution )
        {
            if( $execution['operator'] == 'fetch' )
            {
                $execution['pieceofcode'] = $this->getPieceOfCode( $execution['file'],
                                                                   $execution['linestart'],
                                                                   $execution['lineend'] );
                return $execution;
            }
        }

        return array( 'file'        => 'N/A',
                      'linestart'   => 0,
                      'col'         => 0,
                      'time'        => 0,
                      'pieceofcode' => '' );
    }

    private function getCacheBlocksNumber()
    {
        return $this->getNumberOf( 'cache-block' );
    }

    private function getFetchNumber()
    {
        return $this->getNumberOf( 'fetch' );
    }

    private function sortExecutionStackByTime( $order = 'ASC' )
    {
        // I prefer working on a copy of the attribute for this calculus
        $executionStack = $this->ExecutionStack ;

        $time = array();

        foreach( $this->ExecutionStack as $key => $row )
        {
            $time[$key] = $row['time'];
        }

        if( $order == 'ASC' )
        {
            array_multisort( $time, SORT_ASC, SORT_NUMERIC, $executionStack );
        }
        else
        {
            array_multisort( $time, SORT_DESC, SORT_NUMERIC, $executionStack );
        }

        return $executionStack;
    }

   private function getNumberOf( $operator )
   {
        $number = 0;

        foreach( $this->ExecutionStack as $execution )
        {
            if( $execution['operator'] == $operator )
            {
                $number++;
            }
        }

        return $number;
   }

    public function getPieceOfCode( $file, $lineStart, $lineEnd )
    {
        $pieceOfCode = '';

        $linesArray = file( $file );

        for( $i = $lineStart - 1; $i <= $lineEnd -1; $i++ )
        {
            $pieceOfCode .= $linesArray[$i];
        }

        return $pieceOfCode;
    }

    public function displayExecutionStack()
    {
        $ini = eZINI::instance( 'ezenhanceddebug.ini' );

        $displaySettings = $ini->variable( 'DisplaySettings', 'DisplayResultTable' );

        if( $displaySettings == 'enabled' )
        {
            $tpl =templateInit();

            $tpl->setVariable( 'executionStack'   , $this->ExecutionStack    );
            $tpl->setVariable( 'medianTime'       , $this->getMedianTime()   );
            $tpl->setVariable( 'averageTime'      , $this->getAverageTime()  );
            $tpl->setVariable( 'slowestFetch'     , $this->getSlowestFetch() );
            $tpl->setVariable( 'fetchNumber'      , $this->getFetchNumber()  );
            $tpl->setVariable( 'cacheBlocksNumber', $this->getCacheBlocksNumber()  );

            return $tpl->fetch( "design:ezenhanceddebug/ezenhanceddebug.tpl" );
        }
        return;
    }

    private $ExecutionStack  = array();
    private $TimeStack       = array();
    private $StartTimer      = 0;
    private $StopTimer       = 0;
    private $TotalTime       = 0;
    private $TimingAccuracy  = 4;

    // only used for fetch functions
    public $FetchedRows     = 0;

    private $OperatorFilters = array();
    private $OperatorFilteringIsEnabled;
}
?>
