<?php

function insert($table, $values = NULL, $array = NULL) {
    global $wpdb;
    $table = $wpdb->prefix . $table;
    if ($update == NULL) {
        $wpdb->insert($table, $values, $array);
        return $wpdb->insert_id;
    }
    return $results;
}

function update($table, $values = NULL, $where = NUll, $array = NUll, $wherearray = NULL) {
    global $wpdb;
    $table = $wpdb->prefix . $table;
    $results = $wpdb->update($table, $values, $where, $array, $wherearray);
}

function get_record($from, $where = NULL, $select = NULL, $single = NULL, $and = NULL, $array = NULL) {
    global $wpdb;
    if ($where != NULL) {
        $where = 'WHERE ' . $where;
    } else {
        $where = '';
    }
    if ($and != NULL) {
        $and = 'AND WHERE ' . $and;
    } else {
        $and = '';
    }

    if ($select == NULL) {
        $select = '*';
        $results = $wpdb->get_results("SELECT $select FROM `{$wpdb->prefix}$from` $where $and");
    } else {
        if ($array == NULL) {
            $results = $wpdb->get_results($wpdb->prepare("SELECT $select FROM `{$wpdb->prefix}$from` $where $and", $array));
        } else {
            echo 'Setup is wrong, check function';
        }
    }
    return $results;
}

function delete($table, $id, $column = NULL) {
    global $wpdb;
    $table = $wpdb->prefix . $table;
    if (isset($column) && $column != NULL) {
        $results = $wpdb->query($wpdb->prepare("DELETE FROM $table WHERE $column = %s", $id));
    } else {
        $results = $wpdb->query($wpdb->prepare("DELETE FROM $table WHERE ID = %d", $id));
    }

    return $results;
}

function get_pkg_free_day() {
    global $wpdb, $price_table_name;
    $pricesql = "select validity,validity_per from $price_table_name where status=1 AND package_type = 'pkg_free'";
    $priceinfo = $wpdb->get_results($pricesql);
    return $priceinfo;
}

function get_pkg_onetime_day() {
    global $wpdb, $price_table_name;
    $pricesql = "select validity,validity_per from $price_table_name where status=1 AND package_type = 'pkg_one_time'";
    $priceinfo = $wpdb->get_results($pricesql);
    return $priceinfo;
}
?>
