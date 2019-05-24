namespace WorkTimeLoggerEmulator.ApiClient.DTO
{
    // ReSharper disable once ClassNeverInstantiated.Global
    public class CardQueryResponse
    {
        public string employee;
        public string first_name;
        public string last_name;
        public int worked_today;
        public string open_entry;
        public bool has_invalid_entries;
    }
}