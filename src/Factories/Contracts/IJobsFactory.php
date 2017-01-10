<?php

namespace Ivebe\Lffmpeg\Factories\Contracts;

interface IJobsFactory
{
    public function getDefaultJobInstance();

    public function getThumbsJobInstance();

    public function getEncodeJobInstance();

    public function createDefaultJob($vID);

    public function createThumbsJob($vID);

    public function createEncodeJob($vID, $quality);
}