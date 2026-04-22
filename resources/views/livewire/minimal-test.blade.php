<div>
    <label for="testValue">Test Value:</label>
    <input type="text" id="testValue" wire:model="testValue">
    <div>Current value: <span id="output">{{ $testValue }}</span></div>
</div>
